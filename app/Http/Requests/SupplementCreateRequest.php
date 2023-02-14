<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplementCreateRequest extends FormRequest
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
            'title.max' => trans('main.char250') . " -:- " . trans('main.gTitle'),
            'file_id.max' => trans('main.fileSizeMax'),
            'file_id.file' => trans('main.fileSzieTypeHint'),
            'file_id.mimes' => trans('main.fileTypeMsg'),
        ];
    }

    public function rules()
    {
        return [
            'title' => 'required|max:250',
            'content' => 'required',
            'subject_id' => 'required',
            'file_id' => 'file|mimes:jpeg,jpg,png,doc,docx,rar,zip,pdf|max:8192',
        ];
    }
}
