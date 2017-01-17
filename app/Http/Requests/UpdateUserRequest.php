<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UpdateUserRequest extends FormRequest
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
    public function rules(Request $request)
    {
        return [
            'name' => 'required|min:2|max:25',
            'email' => 'required|min:2|max:50|email|unique:users,email,' . $request->usuario,
            'password' => 'min:2|max:25',
            'role' => 'required|in:user,admin,superadmin',
        ];
    }
}
