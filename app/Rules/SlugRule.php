<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SlugRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $slugRegexpMask = '/^[A-Za-z0-9_-]+$/';
        if (preg_match($slugRegexpMask, $value) !== 1) {
            $fail('Wrong slug format.');
        }
    }
}
