<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileUploadRequest extends FormRequest
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
            'file.max' => trans('main.fileSizeMax'),
            'file.file' => trans('main.fileSzieTypeHint'),
            'file.mimes' => trans('main.fileTypeMsg'),
            'mimes' => trans('main.photoMsg'),
            'image' => trans('main.photoMsg'),
        ];
    }

    public function rules()
    {
        return [
            'file' => 'file|mimes:jpeg,jpg,png,doc,xls,xlsx,docx,ppt,pptx,rar,zip,pdf|max:8192',
        ];
    }
}
