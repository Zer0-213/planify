<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreShiftsRequest extends FormRequest
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
            'shifts' => 'required|array',
            'shifts.*.user_id' => 'required|integer|exists:users,id',
            'shifts.*.shifts' => 'present|array',
            'week' => 'required|date_format:Y-m-d',
        ];
    }
}
