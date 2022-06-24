<?php

namespace App\Http\Requests\Security;

use Illuminate\Foundation\Http\FormRequest;

// TODO: refactor to save role request later and make it encompass both store and update
class StoreRoleRequest extends FormRequest
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
        return [
            'name' => "required|unique:roles,name,{$this->name}|max:100",
        ];
    }
}
