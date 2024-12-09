<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class Identity implements ValidationRule
{
    /**
     * Indicates whether the rule should be implicit.
     *
     * @var bool
     */
    public $implicit = true;

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $id = Str::padLeft($value, 9, '0');
        if (!Str::of($id)->match('/^[0-9]{9}$/')) {
            $fail(__('The :attribute is invalid.'));
        }
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $digit = (int) $id[$i];
            $sum += ($i % 2 === 0) ? $digit : array_sum(str_split($digit * 2));
        }
        if ($sum % 10 > 0) {
            $fail(__('The :attribute is invalid.'));
        }
    }
}
