<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class userPassRequest extends FormRequest
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
            'password.required' => trans('main.required'),
            'password.confirmed' => trans('main.passwordConfirmed'),
            
        ];
    }

    public function rules()
    {
        return [
            'password' => 'required|confirmed|min:6',
        ];
    }
}
