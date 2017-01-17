<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:2|max:25',
            'email' => 'required|min:2|max:50|email|unique:users',
            'password' => 'required|min:2|max:25',
            'role' => 'required|in:user,admin,superadmin',
        ];
    }
}
