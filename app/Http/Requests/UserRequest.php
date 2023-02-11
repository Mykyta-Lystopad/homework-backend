<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'filled|string|min:3|max:50',
            'last_name' => 'filled|string|min:3|max:50',
            'password' => 'filled|string|min:6|max:20',
            'avatar_id' => 'nullable|integer|exists:attachments,id',
            'phone' => 'filled|string|max:13',
        ];
    }
}
