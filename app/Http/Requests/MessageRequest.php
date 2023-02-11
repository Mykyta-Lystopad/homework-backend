<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
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
                'message' => 'required|string|min:3|max:255',
                'assignment_id' => 'required|integer|exists:assignments,id',
                'student_id' => 'required|integer|exists:users,id'
            ];
        } elseif ($this->isMethod('put')) {
            $rules = [
                'message' => 'filled|string|min:3|max:255',
            ];
        }

        return $rules;
    }
}
