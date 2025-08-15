<?php

use App\Models\Native;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('it stores a native record with text type', function () {

    $user = User::factory()->create();
    $this->actingAs($user);

    // No storage faking needed for text
    $payload = [
        'type' => 'text',
        'content' => 'This is a test native text entry',
        'url' => 'https://example.com',
    ];

    $response = $this->postJson('/natives', $payload);

    $response->assertStatus(201);
    $this->assertDatabaseHas('natives', [
        'type' => 'text',
        'content' => 'This is a test native text entry',
        'url' => 'https://example.com',
    ]);
});

test('a native can be stored with an image', function () {
    Storage::fake('public');

    // Create and authenticate a test user
    $user = User::factory()->create();
    $this->actingAs($user);

    $file = UploadedFile::fake()->image('test.jpg');

    $response = $this->postJson('/natives', [
        'type' => 'image',
        'content' => 'Test content',
        'image_path' => $file,
        'url' => 'https://example.com',
    ]);

    $response->assertStatus(201);

    $native = Native::first();

    expect($native)->not()->toBeNull();
    expect($native->type)->toBe('image');
    expect($native->content)->toBe('Test content');

    Storage::disk('public')->assertExists("natives/{$native->image_path}");
});
