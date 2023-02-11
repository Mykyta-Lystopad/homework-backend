<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignmentRequest extends FormRequest
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
                'title' => 'nullable|string|min:3|max:50',
                'description' => 'nullable|string|min:3|max:255',
                'due_date' => 'nullable|date_format:Y-m-d',
                'problems' => 'nullable|array',
                'problems.*.title' => 'nullable|string|min:3|max:255',
                'group_id' => 'required|integer|exists:groups,id',
            ];
        } elseif ($this->isMethod('put')) {
            $rules = [
                'title' => 'nullable|string|min:3|max:50',
                'description' => 'nullable|string|min:3|max:255',
                'due_date' => 'nullable|date_format:Y-m-d'
            ];
        }

        $rules += [
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|exists:attachments,id'
        ];

        return $rules;
    }
}
