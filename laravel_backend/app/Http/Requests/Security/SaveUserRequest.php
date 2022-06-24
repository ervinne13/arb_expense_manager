<?php

namespace App\Http\Requests\Security;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class SaveUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        # TODO: globalize this or make an enum later
        return $this->user()->role === 'Administrator';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => "required|max:100",
            'email' => "required|unique:users|max:100"
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules['email'] = "required|unique:users,email,{$this->id}|max:100";
        } else {
            # we only need the password for user creation
            $rules['password'] = 'required|min:6|confirmed';
        }

        return $rules;
    }
}
