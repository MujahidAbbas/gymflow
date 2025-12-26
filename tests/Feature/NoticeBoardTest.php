<?php

use App\Models\NoticeBoard;
use App\Models\User;

beforeEach(function () {
    $this->owner = User::factory()->create();
    $this->owner->assignRole('owner');
});

test('owner can list notices', function () {
    $notice = NoticeBoard::create([
        'title' => 'Important Notice',
        'content' => 'This is a test notice.',
        'priority' => 'high',
        'publish_date' => now(),
        'is_active' => true,
        'parent_id' => $this->owner->id,
    ]);

    $this->actingAs($this->owner)
        ->get(route('notice-boards.index'))
        ->assertOk()
        ->assertSee($notice->title);
});

test('owner can create notice via ajax', function () {
    $this->actingAs($this->owner)
        ->postJson(route('notice-boards.store'), [
            'title' => 'New Notice',
            'content' => 'Content here',
            'priority' => 'medium',
            'publish_date' => now()->format('Y-m-d'),
            'is_active' => true,
        ])
        ->assertOk()
        ->assertJson(['success' => true]);

    $this->assertDatabaseHas('notice_boards', [
        'title' => 'New Notice',
        'parent_id' => $this->owner->id,
    ]);
});

test('owner can view notice details via ajax', function () {
    $notice = NoticeBoard::create([
        'title' => 'View Notice',
        'content' => 'Content',
        'priority' => 'medium',
        'publish_date' => now(),
        'parent_id' => $this->owner->id,
    ]);

    $this->actingAs($this->owner)
        ->getJson(route('notice-boards.show', $notice))
        ->assertOk()
        ->assertJson([
            'success' => true,
            'notice' => ['title' => $notice->title],
        ]);
});

test('owner can update notice via ajax', function () {
    $notice = NoticeBoard::create([
        'title' => 'Old Title',
        'content' => 'Old Content',
        'priority' => 'medium',
        'publish_date' => now(),
        'parent_id' => $this->owner->id,
    ]);

    $this->actingAs($this->owner)
        ->putJson(route('notice-boards.update', $notice), [
            'title' => 'Updated Title',
            'content' => 'Updated Content',
            'priority' => 'high',
            'publish_date' => now()->format('Y-m-d'),
            'is_active' => true,
        ])
        ->assertOk()
        ->assertJson(['success' => true]);

    $this->assertEquals('Updated Title', $notice->fresh()->title);
});

test('owner can delete notice via ajax', function () {
    $notice = NoticeBoard::create([
        'title' => 'Delete Me',
        'content' => 'Content',
        'priority' => 'medium',
        'publish_date' => now(),
        'parent_id' => $this->owner->id,
    ]);

    $this->actingAs($this->owner)
        ->withoutExceptionHandling()
        ->deleteJson(route('notice-boards.destroy', $notice))
        ->assertOk()
        ->assertJson(['success' => true]);

    $this->assertModelMissing($notice);
});

test('validation works', function () {
    $this->actingAs($this->owner)
        ->postJson(route('notice-boards.store'), [
            'title' => '', // Required
        ])
        ->assertJsonValidationErrors(['title', 'content', 'priority', 'publish_date']);
});

test('owner cannot access other tenant notices', function () {
    $otherOwner = User::factory()->create();
    $otherNotice = NoticeBoard::create([
        'title' => 'Other Notice',
        'content' => 'Content',
        'priority' => 'medium',
        'publish_date' => now(),
        'parent_id' => $otherOwner->id,
    ]);

    $this->actingAs($this->owner)
        ->getJson(route('notice-boards.show', $otherNotice))
        ->assertNotFound();
});
