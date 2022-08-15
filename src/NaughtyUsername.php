<?php
namespace ClaraLeigh\NaughtyUsername;

use Illuminate\Contracts\Validation\Rule;

class NaughtyUsername implements Rule
{
    private StringCheck $stringCheck;

    public function __construct(array $black_lists = [], array $white_lists = [])
    {
        $this->stringCheck = new StringCheck($black_lists, $white_lists);
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
        return $this->stringCheck->validateString($value);
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute contains a naughty word.';
    }
}