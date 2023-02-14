<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HelpeSendRequest extends FormRequest
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
            'mimes' => trans('main.photoMsg'),
            'image' => trans('main.photoMsg'),
            'photo_id.max' => trans('main.photoMsg'),
            //'g-recaptcha-response.required' => 'من فضلك إضغط علي انا لست روبوت! قبل الارسال',
        ];
    }

    public function rules()
    {
        return [
            'sName' => 'required',
            'sMessage' => 'required',
            'user-email' => 'required',
            'user-phone' => 'required',
            'userId' => 'required|min:14|max:14',
            'userIdPhoto1' => 'file|image|mimes:jpeg,jpg,png,gif,webp|max:1024',
            'userIdPhoto2' => 'file|image|mimes:jpeg,jpg,png,gif,webp|max:1024',
            //'g-recaptcha-response' =>  'required|recaptcha',
        ];
    }
}
