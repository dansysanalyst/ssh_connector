<?php

declare(strict_types = 1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class ValidHostRule implements ValidationRule
{
    /**
     * Run the validation rule.
     * Inspired by Laracasts
     *
     * @see https://laracasts.com/discuss/channels/laravel/laravel-rule-to-pass-if-value-is-either-hostname-or-ip
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->isValidFDQN($value) && ! $this->isValidIP($value)) {
            $fail('The value [' . $value . '] is not a valid hostname.');
        }
    }

    private function isValidFDQN(string $value): bool
    {
        return (bool) preg_match('/^(?!:\/\/)(?=.{1,255}$)((.{1,63}\.){1,127}(?![0-9]*$)[a-z0-9-]+\.?)$/i', $value);
    }

    private function isValidIP(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_IP) !== false;
    }
}
