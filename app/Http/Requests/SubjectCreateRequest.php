<?php

namespace App\Http\Requests;
use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SubjectCreateRequest extends FormRequest
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
            'required' => trans('main.required'),
            'required' => trans('main.required'),
            'title.unique' => trans('main.titleUniqueMsg'),
            'title.max' => trans('main.char250'),
        ];
    }

    public function rules()
    {
        return [
            'title' => 'required|max:250|unique:subjects,title,NULL,id,user_id,'. Auth::user()->id,
            'content' => 'required',
        ];
    }
}
