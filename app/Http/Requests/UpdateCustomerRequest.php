<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('edit customers');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Get the tenant ID - route model binding gives us the Tenant model
        $tenantId = $this->route('customer')?->id;

        return [
            'business_name' => 'required|string|max:255',
            'subdomain' => 'nullable|alpha_dash|unique:tenants,subdomain,' . $tenantId,
            'status' => 'required|in:active,suspended,inactive',
            'max_members' => 'required|integer|min:1|max:10000',
            'max_trainers' => 'required|integer|min:1|max:1000',
            'trial_days' => 'nullable|integer|min:0|max:90',
        ];
    }

    /**
     * Get the validation messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'business_name.required' => 'Business name is required.',
            'subdomain.unique' => 'This subdomain is already taken.',
            'status.in' => 'Status must be active, suspended, or inactive.',
        ];
    }
}

