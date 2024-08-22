<?php

namespace App\Rules\v1;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Password implements ValidationRule
{
    protected $minimum_characters = 8;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strlen($value) < $this->minimum_characters) {
            $fail("The {$attribute} must be at least {$this->minimum_characters} characters.");
        }

        // if (!preg_match('/[A-Z]/', $value)) {
        //     $fail("The {$attribute} must contain at least one uppercase letter.");
        // }

        if (!preg_match('/[a-z]/', $value)) {
            $fail("The {$attribute} must contain at least one lowercase letter.");
        }

        if (!preg_match('/[0-9]/', $value)) {
            $fail("The {$attribute} must contain at least one number.");
        }

        // if (!preg_match('/[^A-Za-z0-9]/', $value)) {
        //     $fail("The {$attribute} must contain at least one special character.");
        // }

        // Check for common passwords (you can expand this list)
        // $common_passwords = ['password', '123456', 'qwerty', 'letmein'];
        // if (in_array(strtolower($value), $common_passwords)) {
        //     $fail("The {$attribute} is too common. Please choose a more unique password.");
        // }
    }
}
