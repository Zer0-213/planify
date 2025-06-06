<?php

namespace App\Http\Requests;

use App\Enums\RoleEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateStaffRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => 'nullable|string|max:15',
            'password' => 'required|string|min:8',
            'wage' => 'nullable|numeric|min:0',
            'role' => ['nullable', 'string', Rule::in(array_column(RoleEnum::cases(), 'value'))],];
    }
}
