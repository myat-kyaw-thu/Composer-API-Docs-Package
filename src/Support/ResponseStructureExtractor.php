<?php

namespace myatKyawThu\LaravelApiVisibility\Support;

use ReflectionClass;

/**
 * Extracts API response structure and request payload info
 * purely via static code analysis — NO HTTP execution.
 */
class ResponseStructureExtractor
{
    /** @var array In-request cache keyed by "Class@method" */
    private static array $cache = [];

    /**
     * Main entry point. Returns a structured analysis of a controller method.
     *
     * @return array{success_responses: array, error_responses: array, resources: string[]}
     */
    public function extractFromController(string $controllerClass, string $method): array
    {
        $key = $controllerClass . '@' . $method;
        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }

        $empty = ['success_responses' => [], 'error_responses' => [], 'resources' => []];

        if (!class_exists($controllerClass)) {
            return self::$cache[$key] = $empty;
        }

        try {
            $ref = new ReflectionClass($controllerClass);
            if (!$ref->hasMethod($method)) {
                return self::$cache[$key] = $empty;
            }

            $rm   = $ref->getMethod($method);
            $file = $rm->getFileName();
            if (!$file || !file_exists($file)) {
                return self::$cache[$key] = $empty;
            }

            $lines      = file($file);
            $methodCode = implode('', array_slice(
                $lines,
                $rm->getStartLine() - 1,
                $rm->getEndLine() - $rm->getStartLine() + 1
            ));

            return self::$cache[$key] = $this->analyseMethod($methodCode);
        } catch (\Throwable $e) {
            return self::$cache[$key] = $empty;
        }
    }

    // -------------------------------------------------------------------------
    // Static code analysis
    // -------------------------------------------------------------------------

    private function analyseMethod(string $code): array
    {
        $result = [
            'success_responses' => [],
            'error_responses'   => [],
            'resources'         => [],
        ];

        // Find every response()->json(...) call
        $offset = 0;
        $needle = 'response()->json(';
        while (($pos = strpos($code, $needle, $offset)) !== false) {
            // The '(' is at pos + strlen($needle) - 1
            $parenPos = $pos + strlen($needle) - 1;
            $arg      = $this->extractBalancedParens($code, $parenPos);
            if ($arg !== null) {
                // Strip outer parens to get the argument list
                $inner    = trim(substr($arg, 1, strlen($arg) - 2));
                $status   = $this->extractSecondArg($inner);
                $arrayStr = $this->extractFirstArg($inner);

                // Skip null responses (e.g. response()->json(null, 204))
                if (trim($arrayStr) === 'null' || trim($arrayStr) === '') {
                    $offset = $pos + 1;
                    continue;
                }

                $parsed = $this->parsePhpArray($arrayStr);

                if ($status >= 400) {
                    $result['error_responses'][] = ['status' => $status, 'body' => $parsed];
                } else {
                    $result['success_responses'][] = ['status' => $status ?: 200, 'body' => $parsed];
                }
            }
            $offset = $pos + 1;
        }

        // Detect API Resource usage
        if (preg_match_all('/new\s+([\w\\\\]+Resource)\s*\(/', $code, $m)) {
            $result['resources'] = array_unique($m[1]);
        }

        return $result;
    }

    /**
     * Extract the first argument (the array) from response()->json($array, $status).
     * Handles nested brackets/parens correctly.
     */
    private function extractFirstArg(string $args): string
    {
        $depth = 0;
        $len   = strlen($args);
        $inStr = false;
        $strCh = '';

        for ($i = 0; $i < $len; $i++) {
            $c = $args[$i];

            if (!$inStr && ($c === '"' || $c === "'")) {
                $inStr = true;
                $strCh = $c;
                continue;
            }
            if ($inStr) {
                if ($c === '\\') { $i++; continue; } // skip escaped char
                if ($c === $strCh) { $inStr = false; }
                continue;
            }

            if ($c === '[' || $c === '(') { $depth++; continue; }
            if ($c === ']' || $c === ')') { $depth--; continue; }

            if ($c === ',' && $depth === 0) {
                return trim(substr($args, 0, $i));
            }
        }
        return trim($args);
    }

    /**
     * Extract the HTTP status code (second argument) if present.
     */
    private function extractSecondArg(string $args): int
    {
        $depth = 0;
        $len   = strlen($args);
        $inStr = false;
        $strCh = '';

        for ($i = 0; $i < $len; $i++) {
            $c = $args[$i];

            if (!$inStr && ($c === '"' || $c === "'")) {
                $inStr = true;
                $strCh = $c;
                continue;
            }
            if ($inStr) {
                if ($c === '\\') { $i++; continue; }
                if ($c === $strCh) { $inStr = false; }
                continue;
            }

            if ($c === '[' || $c === '(') { $depth++; continue; }
            if ($c === ']' || $c === ')') { $depth--; continue; }

            if ($c === ',' && $depth === 0) {
                $rest = trim(substr($args, $i + 1));
                if (preg_match('/^(\d{3})/', $rest, $m)) {
                    return (int) $m[1];
                }
                return 0;
            }
        }
        return 0;
    }

    /**
     * Extract balanced parentheses starting at $start (which must point to '(').
     */
    private function extractBalancedParens(string $code, int $start): ?string
    {
        $len = strlen($code);
        if ($start >= $len || $code[$start] !== '(') return null;

        $depth = 0;
        $inStr = false;
        $strCh = '';

        for ($i = $start; $i < $len; $i++) {
            $c = $code[$i];

            if (!$inStr && ($c === '"' || $c === "'")) {
                $inStr = true;
                $strCh = $c;
                continue;
            }
            if ($inStr) {
                if ($c === '\\') { $i++; continue; }
                if ($c === $strCh) { $inStr = false; }
                continue;
            }

            if ($c === '(') { $depth++; continue; }
            if ($c === ')') {
                $depth--;
                if ($depth === 0) return substr($code, $start, $i - $start + 1);
            }
        }
        return null;
    }

    /**
     * Extract balanced brackets starting at $start (which must point to '[').
     */
    private function extractBalancedBrackets(string $src, int $start): ?string
    {
        $len = strlen($src);
        if ($start >= $len || $src[$start] !== '[') return null;

        $depth = 0;
        $inStr = false;
        $strCh = '';

        for ($i = $start; $i < $len; $i++) {
            $c = $src[$i];

            if (!$inStr && ($c === '"' || $c === "'")) {
                $inStr = true;
                $strCh = $c;
                continue;
            }
            if ($inStr) {
                if ($c === '\\') { $i++; continue; }
                if ($c === $strCh) { $inStr = false; }
                continue;
            }

            if ($c === '[') { $depth++; continue; }
            if ($c === ']') {
                $depth--;
                if ($depth === 0) return substr($src, $start, $i - $start + 1);
            }
        }
        return null;
    }

    /**
     * Recursively parse a PHP array literal string into a PHP array.
     * Variables and method calls become descriptive placeholder strings.
     */
    public function parsePhpArray(string $src): array
    {
        $src = trim($src);

        if (str_starts_with($src, '[') && str_ends_with($src, ']')) {
            $inner = substr($src, 1, -1);
        } elseif (str_starts_with($src, 'array(') && str_ends_with($src, ')')) {
            $inner = substr($src, 6, -1);
        } else {
            return [];
        }

        $result = [];
        $tokens = $this->tokeniseArrayBody($inner);

        $i = 0;
        $count = count($tokens);
        while ($i < $count) {
            $tok = $tokens[$i];

            if (isset($tokens[$i + 1]) && $tokens[$i + 1] === '=>') {
                $key          = $this->unquote($tok);
                $value        = $tokens[$i + 2] ?? 'null';
                $result[$key] = $this->resolveValue($value);
                $i += 3;
            } else {
                $result[] = $this->resolveValue($tok);
                $i++;
            }
        }

        return $result;
    }

    /**
     * Tokenise the body of a PHP array (content between the outer brackets).
     * Returns raw token strings: keys, '=>', values.
     */
    private function tokeniseArrayBody(string $src): array
    {
        $tokens = [];
        $i      = 0;
        $len    = strlen($src);

        while ($i < $len) {
            // Skip whitespace and commas
            while ($i < $len && ($src[$i] === ' ' || $src[$i] === "\t" || $src[$i] === "\n" || $src[$i] === "\r" || $src[$i] === ',')) {
                $i++;
            }
            if ($i >= $len) break;

            $c = $src[$i];

            // Arrow operator =>
            if ($c === '=' && $i + 1 < $len && $src[$i + 1] === '>') {
                $tokens[] = '=>';
                $i += 2;
                continue;
            }

            // Quoted string — use proper escape-aware scan
            if ($c === '"' || $c === "'") {
                $quote = $c;
                $j     = $i + 1;
                while ($j < $len) {
                    if ($src[$j] === '\\') { $j += 2; continue; } // skip escaped char
                    if ($src[$j] === $quote) break;
                    $j++;
                }
                $tokens[] = substr($src, $i, $j - $i + 1);
                $i        = $j + 1;
                continue;
            }

            // Nested array [ ... ]
            if ($c === '[') {
                $nested = $this->extractBalancedBrackets($src, $i);
                if ($nested !== null) {
                    $tokens[] = $nested;
                    $i += strlen($nested);
                } else {
                    $i++;
                }
                continue;
            }

            // array( ... )
            if (substr($src, $i, 6) === 'array(') {
                $nested = $this->extractBalancedParens($src, $i + 5);
                if ($nested !== null) {
                    $tokens[] = 'array' . $nested;
                    $i += 5 + strlen($nested);
                } else {
                    $i++;
                }
                continue;
            }

            // Everything else: read until top-level comma or =>
            $start = $i;
            while ($i < $len) {
                $ch = $src[$i];
                if ($ch === ',') break;
                if ($ch === '=' && $i + 1 < $len && $src[$i + 1] === '>') break;
                if ($ch === '(') {
                    $sub = $this->extractBalancedParens($src, $i);
                    if ($sub) { $i += strlen($sub); continue; }
                }
                if ($ch === '[') {
                    $sub = $this->extractBalancedBrackets($src, $i);
                    if ($sub) { $i += strlen($sub); continue; }
                }
                $i++;
            }
            $tok = trim(substr($src, $start, $i - $start));
            if ($tok !== '') $tokens[] = $tok;
        }

        return $tokens;
    }

    /**
     * Resolve a raw token string to a PHP value.
     */
    private function resolveValue(string $raw): mixed
    {
        $raw = trim($raw);

        // Nested array
        if (str_starts_with($raw, '[') || str_starts_with($raw, 'array(')) {
            return $this->parsePhpArray($raw);
        }

        // Quoted string literal
        if (preg_match('/^([\'"])(.*)\1$/s', $raw, $m)) {
            return $m[2];
        }

        // Numeric
        if (is_numeric($raw)) return $raw + 0;

        // Boolean / null
        if (strtolower($raw) === 'true')  return true;
        if (strtolower($raw) === 'false') return false;
        if (strtolower($raw) === 'null')  return null;

        // Variable or expression — produce a readable placeholder
        return $this->makePlaceholder($raw);
    }

    private function makePlaceholder(string $expr): string
    {
        $expr = trim($expr);

        // $result['user']->id  →  "<id>"
        if (preg_match('/->(\w+)$/', $expr, $m)) return '<' . $m[1] . '>';

        // now()->toISOString()  →  "<timestamp>"
        if (str_contains($expr, 'now()')) return '<timestamp>';

        // $variable  →  "<variable>"
        if (str_starts_with($expr, '$')) {
            $clean = preg_replace('/[^a-zA-Z0-9_].*$/', '', ltrim($expr, '$'));
            return '<' . $clean . '>';
        }

        return '<value>';
    }

    private function unquote(string $s): string
    {
        $s = trim($s);
        if (preg_match('/^([\'"])(.*)\1$/s', $s, $m)) return $m[2];
        return $s;
    }

    // -------------------------------------------------------------------------
    // Public helpers used by PreviewController
    // -------------------------------------------------------------------------

    /**
     * Generate a pretty-printed JSON example payload from validation rules.
     */
    public function generateExamplePayload(array $rules): string
    {
        $payload = [];
        foreach ($rules as $field => $ruleList) {
            $ruleList        = is_array($ruleList) ? $ruleList : explode('|', $ruleList);
            $ruleList        = array_map(
                fn($r) => is_object($r) ? strtolower(class_basename($r)) : strtolower((string) $r),
                $ruleList
            );
            $payload[$field] = $this->exampleValue($field, $ruleList);
        }
        return json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    private function exampleValue(string $field, array $rules): mixed
    {
        $f = strtolower($field);

        if (str_contains($f, 'email'))    return 'user@example.com';
        if (str_contains($f, 'password')) return 'Secret123!';
        if ($f === 'name' || str_ends_with($f, '_name')) return 'John Doe';
        if (str_contains($f, 'phone'))    return '+1234567890';
        if (str_contains($f, 'url'))      return 'https://example.com';
        if (str_contains($f, 'token'))    return 'your-token-here';
        if ($f === 'id' || str_ends_with($f, '_id')) return 1;
        if (str_contains($f, 'date'))     return date('Y-m-d');
        if (str_contains($f, 'time'))     return date('H:i:s');
        if (str_contains($f, 'amount') || str_contains($f, 'price')) return 99.99;

        foreach ($rules as $rule) {
            if (str_starts_with($rule, 'in:')) {
                $opts = explode(',', substr($rule, 3));
                return trim($opts[0]);
            }
            if ($rule === 'boolean') return true;
            if ($rule === 'integer' || $rule === 'numeric') return 1;
            if ($rule === 'array')   return [];
            if ($rule === 'email')   return 'user@example.com';
            if ($rule === 'url')     return 'https://example.com';
        }

        return 'example_' . $field;
    }

    /**
     * Render a parsed response body as pretty JSON.
     */
    public function renderMockJson(array $body): string
    {
        return json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /** @deprecated kept for BC */
    public function generateMockResponse(array $structure): string
    {
        return $this->renderMockJson($structure);
    }

    /** @deprecated kept for BC */
    public function formatValidationRulesAsJson(array $rules): string
    {
        return $this->generateExamplePayload($rules);
    }
}
