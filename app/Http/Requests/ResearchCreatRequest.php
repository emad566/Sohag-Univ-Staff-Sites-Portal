<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResearchCreatRequest extends FormRequest
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
            'title.max' => trans('main.char250') . " -:- " . trans('main.gTitle'),
        ];
    }

    public function rules()
    {
        return [
            'facStu_id' => 'required',
            'subject_id' => 'required',
            'title' => 'required|max:250',
            'department' => '|max:250',
            'content' => 'required',
        ];
    }
}
