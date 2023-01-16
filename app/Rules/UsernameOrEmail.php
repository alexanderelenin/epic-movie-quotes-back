<?php

namespace App\Rules;
use App\Models\User;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\App;

class UsernameOrEmail implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return User::where(function ($query) use ($value) {
			$query->orWhere('email', $value)->orWhere('username', $value);
		})->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return App::currentLocale()==='en'? 'Username or email not found': 'მომხმარებელი არ მოიძებნა';
    }
}
