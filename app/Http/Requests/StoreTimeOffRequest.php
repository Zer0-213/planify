<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTimeOffRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'start_date' => 'required|date|after_or_equal:today',
            'start_time' => 'nullable|date_format:H:i',
            'end_date' => 'required|date|after_or_equal:start_date',
            'end_time' => 'nullable|date_format:H:i',
            'reason' => 'nullable|string|max:500',
            'is_full_day' => 'boolean',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'start_date.required' => 'Start date is required.',
            'start_date.after_or_equal' => 'Start date must be today or a future date.',
            'end_date.required' => 'End date is required.',
            'end_date.after_or_equal' => 'End date must be the same as or after the start date.',
            'start_time.date_format' => 'Start time must be in HH:MM format.',
            'end_time.date_format' => 'End time must be in HH:MM format.',
            'reason.max' => 'Reason cannot exceed 500 characters.',
        ];
    }

    public function getTimeOffData(int $companyUserId, bool $includeStatus = false): array
    {
        $data = $this->only([
            'start_date', 'start_time', 'end_date', 'end_time', 'is_full_day', 'reason'
        ]);

        $data['company_user_id'] = $companyUserId;
        $data['is_full_day'] = $data['is_full_day'] ?? false;
        $data['status'] = 'pending';

        if ($includeStatus) {
            $data['status'] = $this->input('status');
        }

        return $data;
    }

}
