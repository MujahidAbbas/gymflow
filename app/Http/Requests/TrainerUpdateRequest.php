<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TrainerUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('edit trainers');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $trainerId = $this->route('trainer');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('trainers')->ignore($trainerId)],
            'phone' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:male,female,other'],
            'address' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'bio' => ['nullable', 'string'],
            'specializations' => ['nullable', 'array'],
            'specializations.*' => ['string'],
            'certifications' => ['nullable', 'array'],
            'certifications.*' => ['string'],
            'years_of_experience' => ['required', 'integer', 'min:0', 'max:50'],
            'hourly_rate' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'status' => ['required', 'in:active,inactive'],
            'notes' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Trainer name is required',
            'email.required' => 'Email address is required',
            'email.unique' => 'This email is already registered',
            'date_of_birth.before' => 'Date of birth must be in the past',
            'years_of_experience.required' => 'Years of experience is required',
        ];
    }
}
