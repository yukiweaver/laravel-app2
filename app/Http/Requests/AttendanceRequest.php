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
          'attendance.*.start_time'     => 'date_format:H:i|string|nullable',
          'attendance.*.end_time'       => 'date_format:H:i|string|nullable',
          'attendance.*.note'           => 'string|max:100|nullable',
          'attendance.*.is_next_day'    => 'boolean',
          'attendance.*.instructor_id'  => 'integer|nullable',
          'current_day'                 => 'required|date',
        ];
        if ($this->attendance_approval) {
          $rules = [];
          $rules = [
            'attendance_approval.*.apply_status'  => 'required|integer|lt:4',
            'attendance_approval.*.change'        => 'required|boolean',
            'current_day'                         => 'required|date',
          ];
        }
        return $rules;
    }
}
