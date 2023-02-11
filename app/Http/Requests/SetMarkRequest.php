<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetMarkRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'teacher_mark' => 'required|integer|min:0|max:100',
        ];
    }
}
