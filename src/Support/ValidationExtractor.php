<?php

namespace Primebeyonder\LaravelApiVisibility\Support;

use Illuminate\Foundation\Http\FormRequest;
use ReflectionClass;
use ReflectionMethod;

class ValidationExtractor
{
    /**
     * Extract validation rules from a class.
     *
     * @param string $className
     * @return array
     */
    public function extractFromClass(string $className): array
    {
        if (!class_exists($className)) {
            return [];
        }

        $reflection = new ReflectionClass($className);

        // Check if it's a FormRequest
        if (!$reflection->isSubclassOf(FormRequest::class)) {
            return [];
        }

        // Try to get rules from the rules() method
        if ($reflection->hasMethod('rules')) {
            try {
                $instance = $reflection->newInstance();
                $rules = $instance->rules();

                return $this->formatRules($rules);
            } catch (\Exception $e) {
                // Silently fail if instantiation fails
            }
        }

        return [];
    }
    /**
     * Create a reflection class (extracted for testing).
     *
     * @param string $className
     * @return \ReflectionClass|null
     */
    protected function createReflectionClass(string $className)
    {
        if (!class_exists($className)) {
            return null;
        }

        return new \ReflectionClass($className);
    }
    /**
     * Format validation rules for display.
     *
     * @param array $rules
     * @return array
     */
    protected function formatRules(array $rules): array
    {
        $formatted = [];

        foreach ($rules as $field => $rule) {
            if (is_string($rule)) {
                $formatted[$field] = explode('|', $rule);
            } elseif (is_array($rule)) {
                $formatted[$field] = $rule;
            }
        }

        return $formatted;
    }
}
