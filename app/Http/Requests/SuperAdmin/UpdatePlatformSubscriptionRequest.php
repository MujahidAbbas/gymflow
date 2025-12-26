<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlatformSubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasRole('super-admin');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $tierId = $this->route('platform_subscription');
        
        return [
            'name' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('platform_subscription_tiers', 'name')->ignore($tierId)],
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'interval' => 'required|in:monthly,quarterly,yearly,lifetime',
            'trial_days' => 'nullable|integer|min:0|max:365',
            'max_members_per_tenant' => 'nullable|integer|min:0',
            'max_trainers_per_tenant' => 'nullable|integer|min:0',
            'max_staff_per_tenant' => 'nullable|integer|min:0',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Get custom attribute names for error messages.
     */
    public function attributes(): array
    {
        return [
            'max_members_per_tenant' => 'member limit',
            'max_trainers_per_tenant' => 'trainer limit',
            'max_staff_per_tenant' => 'staff limit',
        ];
    }
}
