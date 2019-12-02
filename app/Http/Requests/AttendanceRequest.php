<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
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
          'start_time.*' => 'date_format:H:i',
          'end_time.*' => 'date_format:H:i',
          'note.*' => 'string|max:100',
        ];
        return $rules;
    }
}
