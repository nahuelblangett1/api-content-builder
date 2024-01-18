<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PercentageRange implements Rule
{
    public function passes($attribute, $value)
    {
        return $value >= 10 && $value <= 100;
    }

    public function message()
    {
        return 'output_percentage must be between 10 and 100';
    }
}
