<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskCreatRequest extends FormRequest
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
            'mimes' => trans('main.photoMsg'),
            'image' => trans('main.photoMsg'),
            'photo_id.max' => trans('main.photoMsg'),
        ];
    }

    public function rules()
    {
        return [
            'subject_id' => 'required',
            'title' => 'required|max:250',
            'content' => 'required',
            'fullDegree' => 'required',
            'photo_id' => 'file|image|mimes:jpeg,jpg,png,gif,webp|max:650',
        ];
    }
}
