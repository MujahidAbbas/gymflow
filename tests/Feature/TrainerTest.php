<?php

use App\Models\Trainer;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->owner = User::factory()->create();
    $this->owner->assignRole('owner');
});

test('owner can list trainers', function () {
    $trainer = Trainer::create([
        'name' => 'John Trainer',
        'email' => 'john@trainer.com',
        'phone' => '1234567890',
        'status' => 'active',
        'specializations' => ['Yoga', 'Pilates'],
        'certifications' => ['Certified Yoga Instructor'],
        'parent_id' => $this->owner->id,
    ]);

    $this->actingAs($this->owner)
        ->get(route('trainers.index'))
        ->assertOk()
        ->assertSee($trainer->name);
});

test('owner can create trainer with photo and details', function () {
    Storage::fake('public');
    $photo = UploadedFile::fake()->image('trainer.jpg');

    $this->actingAs($this->owner)
        ->post(route('trainers.store'), [
            'name' => 'Jane Trainer',
            'email' => 'jane@trainer.com',
            'phone' => '0987654321',
            'status' => 'active',
            'specializations' => ['HIIT', 'Cardio'],
            'certifications' => ['ACE Certified'],
            'hourly_rate' => 50,
            'photo' => $photo,
            'gender' => 'female',
            'address' => '123 Gym St',
            'years_of_experience' => 3,
        ])
        ->assertRedirect(route('trainers.index'));

    $this->assertDatabaseHas('trainers', [
        'email' => 'jane@trainer.com',
        'parent_id' => $this->owner->id,
    ]);

    $trainer = Trainer::where('email', 'jane@trainer.com')->first();
    $this->assertNotNull($trainer->trainer_id);
    $this->assertEquals(['HIIT', 'Cardio'], $trainer->specializations);
    Storage::disk('public')->assertExists($trainer->photo);
});

test('owner can update trainer', function () {
    $trainer = Trainer::create([
        'name' => 'John Trainer',
        'email' => 'john@trainer.com',
        'parent_id' => $this->owner->id,
        'status' => 'active',
    ]);

    $this->actingAs($this->owner)
        ->put(route('trainers.update', $trainer), [
            'name' => 'John Updated',
            'email' => 'john@trainer.com',
            'status' => 'inactive',
            'gender' => 'male',
            'specializations' => ['Boxing'],
            'years_of_experience' => 5,
        ])
        ->assertRedirect(route('trainers.index'));

    $this->assertEquals('John Updated', $trainer->fresh()->name);
    $this->assertEquals(['Boxing'], $trainer->fresh()->specializations);
});

test('owner can delete trainer', function () {
    $trainer = Trainer::create([
        'name' => 'John Trainer',
        'email' => 'john@trainer.com',
        'parent_id' => $this->owner->id,
        'status' => 'active',
    ]);

    $this->actingAs($this->owner)
        ->delete(route('trainers.destroy', $trainer))
        ->assertRedirect(route('trainers.index'));

    $this->assertModelMissing($trainer);
});

test('owner cannot access trainers from other tenant', function () {
    $otherOwner = User::factory()->create();
    $otherTrainer = Trainer::create([
        'name' => 'Other Trainer',
        'email' => 'other@trainer.com',
        'parent_id' => $otherOwner->id,
        'status' => 'active',
    ]);

    $this->actingAs($this->owner)
        ->get(route('trainers.show', $otherTrainer))
        ->assertForbidden();

    $this->actingAs($this->owner)
        ->put(route('trainers.update', $otherTrainer), [
            'name' => 'Hacked',
            'email' => 'hacked@trainer.com',
            'phone' => '1234567890',
            'status' => 'active',
            'specializations' => ['Hacking'],
            'certifications' => ['Certified Hacker'],
            'hourly_rate' => 100,
            'years_of_experience' => 5,
            'gender' => 'male',
            'address' => '123 Hack St',
        ])
        ->assertForbidden();

    $this->actingAs($this->owner)
        ->delete(route('trainers.destroy', $otherTrainer))
        ->assertForbidden();
});
