<?php

use App\Models\PlatformSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create roles
    foreach (['super-admin', 'owner'] as $role) {
        Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
    }
});

test('super admin can view platform settings page', function () {
    $superAdmin = User::factory()->create(['email_verified_at' => now()]);
    $superAdmin->assignRole('super-admin');

    $response = $this->actingAs($superAdmin)->get(route('super-admin.settings.index'));

    $response->assertStatus(200);
    $response->assertSee('Platform Settings');
});

test('owner cannot access platform settings page', function () {
    $owner = User::factory()->create(['parent_id' => null, 'email_verified_at' => now()]);
    $owner->assignRole('owner');

    $response = $this->actingAs($owner)->get(route('super-admin.settings.index'));

    $response->assertStatus(403);
});

test('super admin can update platform settings', function () {
    $superAdmin = User::factory()->create(['email_verified_at' => now()]);
    $superAdmin->assignRole('super-admin');

    $response = $this->actingAs($superAdmin)->put(route('super-admin.settings.update'), [
        'platform_name' => 'My Custom Platform',
        'enable_registration' => true,
        'enable_landing_page' => false,
        'default_currency' => 'EUR',
    ]);

    $response->assertRedirect(route('super-admin.settings.index'));
    $response->assertSessionHas('success');

    expect(PlatformSetting::where('key', 'platform_name')->first()->value)->toBe('My Custom Platform');
    expect(PlatformSetting::where('key', 'enable_registration')->first()->value)->toBe('1');
    expect(PlatformSetting::where('key', 'enable_landing_page')->first()->value)->toBe('0');
    expect(PlatformSetting::where('key', 'default_currency')->first()->value)->toBe('EUR');
});

test('settings are cached correctly', function () {
    PlatformSetting::set('test_setting', 'test_value');

    expect(PlatformSetting::get('test_setting'))->toBe('test_value');
    
    // Verify caching works
    PlatformSetting::where('key', 'test_setting')->update(['value' => 'updated_value']);
    
    // Should still return cached value
    expect(PlatformSetting::get('test_setting'))->toBe('test_value');
    
    // After clearing cache, should return new value
    PlatformSetting::clearCache();
    expect(PlatformSetting::get('test_setting'))->toBe('updated_value');
});

test('boolean settings are cast correctly', function () {
    PlatformSetting::set('enable_feature', '1', 'boolean');
    expect(PlatformSetting::get('enable_feature'))->toBeTrue();

    PlatformSetting::set('enable_feature', '0', 'boolean');
    PlatformSetting::clearCache();
    expect(PlatformSetting::get('enable_feature'))->toBeFalse();
});

test('settings are grouped correctly', function () {
    PlatformSetting::set('key1', 'value1', 'string', 'group1');
    PlatformSetting::set('key2', 'value2', 'string', 'group1');
    PlatformSetting::set('key3', 'value3', 'string', 'group2');

    $grouped = PlatformSetting::getAllGrouped();

    expect($grouped)->toHaveKey('group1');
    expect($grouped)->toHaveKey('group2');
    expect($grouped['group1'])->toHaveCount(2);
    expect($grouped['group2'])->toHaveCount(1);
});

test('default value is returned when setting does not exist', function () {
    expect(PlatformSetting::get('nonexistent_key', 'default_value'))->toBe('default_value');
});

test('owner cannot update platform settings', function () {
    $owner = User::factory()->create(['parent_id' => null, 'email_verified_at' => now()]);
    $owner->assignRole('owner');

    $response = $this->actingAs($owner)->put(route('super-admin.settings.update'), [
        'platform_name' => 'Hacked Name',
    ]);

    $response->assertStatus(403);
});
