<?php

namespace App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class PostCreatRequest extends FormRequest
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
            'file_id.max' => trans('main.fileSizeMax'),
            'title.unique' => trans('main.titleUniqueMsg'),
            'title.max' => trans('main.char400'),

            'file_id.file' => trans('main.fileSzieTypeHint'),
            'file_id.mimes' => trans('main.fileTypeMsg'),

            'mimes' => trans('main.photoMsg'),
            'image' => trans('main.photoMsg'),
            'photo_id.max' => trans('main.photoMsg'),

            'year.max' => trans('main.num4'),
            'auther.max' => trans('main.char400'),
            'url.max' => trans('main.char400'),
            'urlTitle.max' => trans('main.char400'),
            'journal.max' => trans('main.char400'),
            'num.max' => trans('main.char400'),
            'yearNum.max' => trans('main.char400'),
        ];
    }

    public function rules()
    {
        return [
            'title' => 'required|max:400|unique:posts,title,NULL,id,user_id,'. Auth::user()->id,
            //'title' => 'required|unique:posts,title,',
            'content' => 'required',
            'file_id' => 'file|mimes:jpeg,jpg,png,doc,docx,rar,zip,pdf|max:8192',
            'photo_id' => 'file|image|mimes:jpeg,jpg,png,gif,webp|max:300',

            'year' => 'max:4',
            'auther' => 'max:400',
            'url' => 'max:400',
            'urlTitle' => 'max:400',
            'journal' => 'max:400',
            'num' => 'max:14',
            'yearNum' => 'max:4',
        ];
    }

    
}
