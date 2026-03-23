<?php

namespace myatKyawThu\LaravelApiVisibility\Support;

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
                // Try to instantiate without constructor arguments first
                $instance = $reflection->newInstanceWithoutConstructor();
                $rules = $instance->rules();

                return $this->formatRules($rules);
            } catch (\Exception $e) {
                // If that fails, try with constructor
                try {
                    $instance = $reflection->newInstance();
                    $rules = $instance->rules();
                    return $this->formatRules($rules);
                } catch (\Exception $e2) {
                    // Silently fail if instantiation fails
                }
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
                // Convert Rule objects to their class names
                $formatted[$field] = array_map(function($item) {
                    if (is_object($item)) {
                        return class_basename(get_class($item));
                    }
                    return (string)$item;
                }, $rule);
            } elseif (is_object($rule)) {
                // Single Rule object
                $formatted[$field] = [class_basename(get_class($rule))];
            }
        }

        return $formatted;
    }
}
