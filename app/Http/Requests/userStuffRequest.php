<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class userStuffRequest extends FormRequest
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
            'fullName.required' => trans('main.required') . " -:- " . trans('main.user-fullName'),
            //'brithDate.required' => trans('main.required') . " -:- " . trans('main.user-brithDate'),
            'degree.required' => trans('main.required') . " -:- " . trans('main.user-degree'),
            'faculty_id.required' => trans('main.required') . " -:- " . trans('main.user-faculty_id'),
            'sex.required' => trans('main.required') . " -:- " . trans('main.user-sex'),
            'title.required' => trans('main.required') . " -:- " . trans('main.gTitle'),
            'webIntro.required' => trans('main.required') . " -:- " . trans('main.user-webIntro'),
            'title.min' => trans('main.minMax') . " -:- " . trans('main.user-title'),
            'mimes' => trans('main.photoMsg') . " -:- " . trans('main.user-photo_id'),
            'image' => trans('main.photoMsg') . " -:- " . trans('main.user-photo_id'),
            'photo_id.max' => trans('main.photoMsg') . " -:- " . trans('main.user-photo_id'),
            'brithDate.date_format' => trans('main.user-brithDateHint') . " -:- " . trans('main.user-brithDate'),

            'fullName.min' => trans('main.nameMinMsg') . " -:- " . trans('main.user-fullName'),
            'fullName.max' => trans('main.nameMaxMsg') . " -:- " . trans('main.user-fullName'),
            'title.max' => trans('main.char200') . " -:- " . trans('main.address'),
            'currentPosition.max' => trans('main.char400') . " -:- " . trans('main.user-currentPosition'),
            'positions.max' => trans('main.char400') . " -:- " . trans('main.positions'),
            
            'general.max' => trans('main.char400') . " -:- " . trans('main.general'),
            'special.max' => trans('main.char400') . " -:- " . trans('main.special'),
            'masterar.max' => trans('main.char400') . " -:- " . trans('main.masterar'),
            'masteren.max' => trans('main.char400') . " -:- " . trans('main.masteren'),
            'phdar.max' => trans('main.char400') . " -:- " . trans('main.phdar'),
            'phden.max' => trans('main.char400') . " -:- " . trans('main.phden'),
            
            'webIntro.min' => trans('main.webIntroMin') . " -:- " . trans('main.user-webIntro'),

            'phone.max' => trans('main.num14') . " -:- " . trans('main.user-phone'),
            'fax.max' => trans('main.num14') . " -:- " . trans('main.user-fax'),
            'mobile.max' => trans('main.num14') . " -:- " . trans('main.user-mobile'),

            'fb.max' => trans('main.char400') . " -:- " . trans('main.user-fb'),
            'twitter.max' => trans('main.char400') . " -:- " . trans('main.user-twitter'),
            'yt.max' => trans('main.char400') . " -:- " . trans('main.user-yt'),
            'linkedIn.max' => trans('main.char400') . " -:- " . trans('main.user-linkedIn'),
            'googlePlus.max' => trans('main.char400') . " -:- " . trans('main.user-googlePlus'),
            'schooler.max' => trans('main.char400') . " -:- " . trans('main.user-schooler'),
        ];
    }

    public function rules()
    {
        return [
            'fullName' => 'required|min:6|max:150',
            //'brithDate' => 'required',
            'degree' => 'required',
            'faculty_id' => 'required',
            'photo_id' => 'file|image|mimes:jpeg,jpg,png,gif,webp|max:300',
            'webIntro' => 'required|min:100',
            'title' => 'required|min:6|max:150',
            'sex' =>'required',
            'brithDate' => 'date_format:"Y-m-d"|nullable',

            'title' => 'max:200',
            'currentPosition' => 'max:400',
            'positions' => 'max:400',

            'general' => 'required|max:400',
            'special' => 'max:400',
            'masterar' => 'max:400',
            'masteren' => 'max:400',
            'phdar' => 'max:400',
            'phden' => 'max:400',

            'phone' => 'max:14',
            'fax' => 'max:14',
            'mobile' => 'max:14',

            'fb' => 'max:400',
            'twitter' => 'max:400',
            'yt' => 'max:400',
            'linkedIn' => 'max:400',
            'googlePlus' => 'max:400',
            'schooler' => 'max:400',
        ];
    }

        
}
