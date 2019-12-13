<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|email:rfc',
            'belong' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|same:password',
            'number' => 'nullable|string|max:255',
            'card_number' => 'nullable|string|max:255',
            'basic_work_time' => 'nullable|string',
            'designate_start_time' => 'nullable|string',
            'designate_end_time' => 'nullable|string',
        ];

        // 入力フィールドが存在しない、or値が空ならバリデーションから除外
        if (empty($this->password)) {
          unset($rules['password']);
        }
        if (empty($this->password_confirmation)) {
          unset($rules['password_confirmation']);
        }

        if ($this->csv_file) {
          $rules = [];
          $rules['csv_file'] = 'required|file|mimetypes:text/plain|mimes:csv,txt';
        }

        return $rules;
    }
}
