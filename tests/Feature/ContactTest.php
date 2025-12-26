<?php

use App\Models\Contact;
use App\Models\User;

beforeEach(function () {
    $this->owner = User::factory()->create();
    $this->owner->assignRole('owner');
});

test('owner can list contacts', function () {
    $contact = Contact::create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'contact_number' => '1234567890',
        'subject' => 'Business Inquiry',
        'message' => 'Looking for gym partnership',
        'parent_id' => $this->owner->id,
    ]);

    $this->actingAs($this->owner)
        ->get(route('contacts.index'))
        ->assertOk()
        ->assertSee($contact->name)
        ->assertSee($contact->email);
});

test('owner can create contact with all fields', function () {
    $this->actingAs($this->owner)
        ->post(route('contacts.store'), [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'contact_number' => '0987654321',
            'subject' => 'Partnership Opportunity',
            'message' => 'Interested in collaboration',
        ])
        ->assertRedirect(route('contacts.index'));

    $this->assertDatabaseHas('contacts', [
        'name' => 'Jane Smith',
        'email' => 'jane@example.com',
        'contact_number' => '0987654321',
        'parent_id' => $this->owner->id,
    ]);
});

test('owner can create contact with only required name field', function () {
    $this->actingAs($this->owner)
        ->post(route('contacts.store'), [
            'name' => 'John Doe',
            'email' => null,
            'contact_number' => null,
            'subject' => null,
            'message' => null,
        ])
        ->assertRedirect(route('contacts.index'));

    expect(Contact::where('parent_id', $this->owner->id)->count())->toBe(1);
});

test('name is required when creating contact', function () {
    $this->actingAs($this->owner)
        ->post(route('contacts.store'), [
            'name' => null,
            'email' => 'test@example.com',
        ])
        ->assertSessionHasErrors('name');
});

test('owner can search contacts', function () {
    Contact::create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'subject' => 'Business',
        'parent_id' => $this->owner->id,
    ]);

    Contact::create([
        'name' => 'Jane Smith',
        'email' => 'jane@example.com',
        'subject' => 'Partnership',
        'parent_id' => $this->owner->id,
    ]);

    $this->actingAs($this->owner)
        ->get(route('contacts.index', ['search' => 'Jane']))
        ->assertOk()
        ->assertSee('Jane Smith')
        ->assertDontSee('John Doe');
});

test('owner can view contact details via ajax', function () {
    $contact = Contact::create([
        'name' => 'Test Contact',
        'email' => 'test@example.com',
        'contact_number' => '1234567890',
        'subject' => 'Test Subject',
        'message' => 'Test message content',
        'parent_id' => $this->owner->id,
    ]);

    $this->actingAs($this->owner)
        ->getJson(route('contacts.show', $contact))
        ->assertOk()
        ->assertJson([
            'success' => true,
            'contact' => [
                'name' => $contact->name,
                'email' => $contact->email,
                'contact_number' => $contact->contact_number,
                'subject' => $contact->subject,
                'message' => $contact->message,
            ],
        ]);
});

test('non-ajax show request redirects to index', function () {
    $contact = Contact::create([
        'name' => 'Test Contact',
        'email' => 'test@example.com',
        'parent_id' => $this->owner->id,
    ]);

    $this->actingAs($this->owner)
        ->get(route('contacts.show', $contact))
        ->assertRedirect(route('contacts.index'));
});

test('owner can update contact', function () {
    $contact = Contact::create([
        'name' => 'Original Name',
        'email' => 'original@example.com',
        'parent_id' => $this->owner->id,
    ]);

    $this->actingAs($this->owner)
        ->put(route('contacts.update', $contact), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'contact_number' => '9999999999',
            'subject' => 'Updated Subject',
            'message' => 'Updated message',
        ])
        ->assertRedirect(route('contacts.index'));

    $this->assertEquals('Updated Name', $contact->fresh()->name);
    $this->assertEquals('updated@example.com', $contact->fresh()->email);
    $this->assertEquals('9999999999', $contact->fresh()->contact_number);
});

test('owner can delete contact', function () {
    $contact = Contact::create([
        'name' => 'Test Contact',
        'email' => 'test@example.com',
        'parent_id' => $this->owner->id,
    ]);

    $this->actingAs($this->owner)
        ->delete(route('contacts.destroy', $contact))
        ->assertRedirect(route('contacts.index'));

    $this->assertModelMissing($contact);
});

test('email validation works correctly', function () {
    $this->actingAs($this->owner)
        ->post(route('contacts.store'), [
            'name' => 'Test',
            'email' => 'invalid-email',
        ])
        ->assertSessionHasErrors('email');
});

test('owner cannot access contacts from other tenant', function () {
    $otherOwner = User::factory()->create();
    $otherContact = Contact::create([
        'name' => 'Other Contact',
        'email' => 'other@example.com',
        'parent_id' => $otherOwner->id,
    ]);

    $this->actingAs($this->owner)
        ->get(route('contacts.show', $otherContact))
        ->assertForbidden();

    $this->actingAs($this->owner)
        ->put(route('contacts.update', $otherContact), [
            'name' => 'Hacked Name',
            'email' => 'hacked@example.com',
        ])
        ->assertForbidden();

    $this->actingAs($this->owner)
        ->delete(route('contacts.destroy', $otherContact))
        ->assertForbidden();
});

test('contacts are properly scoped to parent_id', function () {
    $owner1 = User::factory()->create();
    $owner1->assignRole('owner');
    
    $owner2 = User::factory()->create();
    $owner2->assignRole('owner');

    Contact::create([
        'name' => 'Owner 1 Contact',
        'parent_id' => $owner1->id,
    ]);

    Contact::create([
        'name' => 'Owner 2 Contact',
        'parent_id' => $owner2->id,
    ]);

    $this->actingAs($owner1)
        ->get(route('contacts.index'))
        ->assertSee('Owner 1 Contact')
        ->assertDontSee('Owner 2 Contact');

    $this->actingAs($owner2)
        ->get(route('contacts.index'))
        ->assertSee('Owner 2 Contact')
        ->assertDontSee('Owner 1 Contact');
});
