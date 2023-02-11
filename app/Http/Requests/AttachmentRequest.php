<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttachmentRequest extends FormRequest
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
                'comment' => 'nullable|string|min:3|max:255',
                'file_name' => 'required|string|min:3|max:50',
                'file_content' => 'required|base64file|base64max:2000|base64mimes:jpeg,bmp,png,doc,docx'
            ];
        } elseif ($this->isMethod('put')) {
            $rules = [
                'comment' => 'nullable|string|min:3|max:255',
                'file_name' => 'required_with:file_content|string|min:3|max:50',
                'file_content' => 'required_with:file_name|base64file|base64max:2000|base64mimes:jpeg,bmp,png,doc,docx'
            ];
        }

        return $rules;
    }
}
