<?php

if (! function_exists('field_error')) {
    function field_error(string $field, array $errors = []): string
    {
        if (empty($errors)) {
            $errors = session('errors') ?? [];
        }

        if (! isset($errors[$field])) {
            return '';
        }

        return '<span class="text-xs text-error mt-xs font-semibold">' . esc($errors[$field]) . '</span>';
    }
}

if (! function_exists('has_error')) {
    function has_error(string $field, array $errors = []): bool
    {
        if (empty($errors)) {
            $errors = session('errors') ?? [];
        }

        return isset($errors[$field]);
    }
}
