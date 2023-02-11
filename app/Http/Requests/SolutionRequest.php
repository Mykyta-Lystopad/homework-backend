<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SolutionRequest extends FormRequest
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
                'completed' => 'required|boolean',
                'problem_id' => 'required|integer|exists:problems,id',
            ];
        } elseif ($this->isMethod('put')) {
            $rules = [
                'completed' => 'required|boolean',
            ];
        }

        return $rules;
    }
}
