<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        if ($this->isMethod('post')) {
            $rules = [
                'title' => 'required|string|max:3',
                'note' => 'nullable|string|max:50',
                'subject_id' => 'required|integer|exists:subjects,id'
            ];
        } elseif ($this->isMethod('put')) {
            $rules = [
                'title' => 'nullable|string|max:3',
                'note' => 'nullable|string|max:50',
                'subject_id' => 'nullable|integer|exists:subjects,id'
            ];
        }

        return $rules;
    }
}
