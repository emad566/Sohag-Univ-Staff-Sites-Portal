<?php

namespace App\Http\Requests;
use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AdvCreateRequest extends FormRequest
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
            'mimes' => trans('main.photoMsg'),
            'image' => trans('main.photoMsg'),
            'photo_id.max' => trans('main.photoMsg'),
        ];
    }

    public function rules()
    {
        return [
            'title' => 'required|unique:advs,title,NULL,id,user_id,'. Auth::user()->id,
            'content' => 'required',
            'photo_id' => 'file|image|mimes:jpeg,jpg,png,gif,webp|max:300',
        ];
    }
}
