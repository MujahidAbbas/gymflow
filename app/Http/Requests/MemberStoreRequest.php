<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create members');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:members'],
            'phone' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:male,female,other'],
            'address' => ['nullable', 'string'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'medical_conditions' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'membership_plan_id' => ['required', 'exists:membership_plans,id'],
            'membership_start_date' => ['required', 'date'],
            'status' => ['required', 'in:active,inactive,expired,suspended'],
            'notes' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Member name is required',
            'email.required' => 'Email address is required',
            'email.unique' => 'This email is already registered',
            'date_of_birth.before' => 'Date of birth must be in the past',
            'membership_plan_id.required' => 'Please select a membership plan',
            'membership_plan_id.exists' => 'Invalid membership plan selected',
            'membership_start_date.required' => 'Membership start date is required',
        ];
    }
}
