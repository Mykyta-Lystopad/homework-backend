<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProblemRequest extends FormRequest
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
                'title' => 'required|string|min:3|max:50',
                'assignment_id' => 'required|integer|exists:assignments,id'
            ];
        } elseif ($this->isMethod('put')) {
            $rules = [
                'title' => 'nullable|string|min:3|max:50',
            ];
        }

        return $rules;
    }
}

