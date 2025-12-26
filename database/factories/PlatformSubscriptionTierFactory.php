<?php

namespace Database\Factories;

use App\Models\PlatformSubscriptionTier;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlatformSubscriptionTierFactory extends Factory
{
    protected $model = PlatformSubscriptionTier::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true).' Plan',
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 19, 299),
            'interval' => $this->faker->randomElement(['monthly', 'quarterly', 'yearly', 'lifetime']),
            'trial_days' => $this->faker->randomElement([0, 7, 14, 30]),
            'max_members_per_tenant' => $this->faker->randomElement([50, 100, 200, 500, null]),
            'max_trainers_per_tenant' => $this->faker->randomElement([5, 10, 20, 50, null]),
            'max_staff_per_tenant' => $this->faker->randomElement([2, 5, 10, 20, null]),
            'features' => [
                'Member Management',
                'Class Scheduling',
                'Attendance Tracking',
            ],
            'is_active' => true,
            'is_featured' => false,
            'sort_order' => $this->faker->numberBetween(0, 10),
        ];
    }

    /**
     * Indicate that the tier is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    /**
     * Indicate that the tier is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the tier has unlimited limits.
     */
    public function unlimited(): static
    {
        return $this->state(fn (array $attributes) => [
            'max_members_per_tenant' => null,
            'max_trainers_per_tenant' => null,
            'max_staff_per_tenant' => null,
        ]);
    }
}
