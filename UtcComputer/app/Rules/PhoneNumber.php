<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneNumber implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $pattern = "/(^(0|\+84)((3[2-9])|(5[689])|(7[06-9])|(8[1-689])|(9[0-46-9]))(\d){7}$)|(^(0|\+84)((3[2-9])|(5[689])|(7[06-9])|(8[1-689])|(9[0-46-9]))(\d){7}$)/";
        return preg_match($pattern, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Không đúng định dạng số điện thoại Việt Nam';
    }
}