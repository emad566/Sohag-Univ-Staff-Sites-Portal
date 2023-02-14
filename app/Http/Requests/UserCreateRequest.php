<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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

    public function messages()
    {
        return [
            'name.required' => trans('main.required'),
            'email.required' => trans('main.required'),
            'role_id.required' => trans('main.required'),
            'password.required' => trans('main.required'),
            'userID.required' => trans('main.required'),
            'sex.required' => trans('main.required'),
            'faculty_id.require d' => trans('main.required'),
            
            'name.unique' => trans('main.nameUnique'),
            'email.unique' => trans('main.emailUnique'),
            'password.confirmed' => trans('main.passwordConfirmed'),
            'name.min' => trans('main.nameMinMax'),
            'name.max' => trans('main.nameMinMax'),

        ];
    }

    public function rules()
    {
        return [
            'name' => 'required|min:6|max:150|unique:users',
            'email' => 'required|unique:users',
            'role_id' => 'required',
            'userID' => 'required|unique:users',
            'sex' => 'required',
            'faculty_id' => 'required',
            'password' => 'required|confirmed|min:6',
        ];
    }
}
