<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class answerCreateRequest extends FormRequest
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
            'name.required' => trans('main.required') . ": " . trans('main.answerH-name'),
            'task_id.required' => trans('main.required') . ": " . trans('main.answerH-task_id'),
            'faculty_id.required' => trans('main.required') . ": " . trans('main.answerH-facutly_id'),
            'department.required' => trans('main.required') . ": " . trans('main.answerH-department'),
            'level.required' => trans('main.required') . ": " . trans('main.answerH-level'),
            'email.required' => trans('main.required') . ": " . trans('main.answerH-email'),
            'stuId.required' => trans('main.required') . ": " . trans('main.stuId'),
            'stuId.max' => trans('main.num14') . ": " . trans('main.stuId'),
            'stuId.min' => trans('main.num14') . ": " . trans('main.stuId'),
        ];
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'task_id' => 'required',
            'faculty_id' => 'required',
            'department' => 'required',
            'level' => 'required',
            // 'email' => 'required',
            'stuId' => 'required|min:14|max:14',
        ];
    }
}
