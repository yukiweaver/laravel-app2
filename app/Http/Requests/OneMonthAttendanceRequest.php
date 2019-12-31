<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OneMonthAttendanceRequest extends FormRequest
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
          'instructor_id' => 'required|integer',
          'current_day'   => 'required|date',
        ];

        if ($this->one_month_attendance) {
          $rules = [];
          $rules['one_month_attendance.*.apply_status'] = 'required|integer|lt:4'; // 4より小さいか
          $rules['one_month_attendance.*.change'] = 'required|boolean';
          $rules['current_day'] = 'required|date';
        }

        return $rules;
    }
}
