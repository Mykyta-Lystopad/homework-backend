<?php

namespace App\Http\Requests\Admin;

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
                'subject_id' => 'required|integer|exists:subjects,id',
                'user_id' => 'required|integer|exists:users,id'
            ];
        } elseif ($this->isMethod('put')) {
            $rules = [
                'title' => 'filled|string|max:3',
                'note' => 'nullable|string|max:50',
                'subject_id' => 'filled|integer|exists:subjects,id',
                'user_id' => 'filled|integer|exists:users,id'
            ];
        }

        return $rules;
    }
}
