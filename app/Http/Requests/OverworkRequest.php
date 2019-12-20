<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OverworkRequest extends FormRequest
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
        'attendance_id'         => 'required|integer',
        'scheduled_end_time'    => 'required|string',
        'is_next_day'           => 'required|boolean',
        'business_description'  => 'nullable|string|max:255',
        'instructor_id'         => 'required|integer',
      ];

      return $rules;
    }
}
