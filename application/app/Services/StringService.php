<?php

namespace App\Services;

class StringService
{
    public function sanitizeBooleanInput(string $input): string
    {
        // MySQL boolean mode special characters
        $specials = [
            '+', '-', '@', '<', '>', '(', ')', '~',
            '*', '"', "'", ':', '=', '^', '$', '&',
            '|', '!', '?', '#', '%', '/', '\\'
        ];

        // Replace all special chars with spaces
        $clean = str_replace($specials, ' ', $input);

        // Normalize multiple spaces
        $clean = preg_replace('/\s+/', ' ', $clean);

        return trim($clean);
    }
}
