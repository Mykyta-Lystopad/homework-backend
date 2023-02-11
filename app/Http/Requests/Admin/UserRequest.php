<?php

namespace App\Http\Requests\Admin;

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
        $rules = [];
        if ($this->isMethod('post')) {
            $rules = [
                'first_name' => 'required|string|min:3|max:50',
                'last_name' => 'required|string|min:3|max:50',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6|max:20'
            ];
        } elseif ($this->isMethod('put')) {
            $rules = [
                'first_name' => 'filled|string|min:3|max:50',
                'last_name' => 'filled|string|min:3|max:50',
                'email' => 'filled|email|unique:users,email,' . $this->route('user')->id,
                'password' => 'filled|string|min:6|max:20'
            ];
        }

        return $rules;
    }
}
