<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;

function authUser()
{
    $user = User::factory()->create();
    test()->actingAs($user, 'sanctum');

    return $user;
}

test('get native list', function () {
    authUser();

    $response = $this->getJson('/api/natives');

    $response->assertOk()
        ->assertJsonStructure([
            '*' => [
                'id',
                'type',
                'content',
                'image_path',
                'url',
                'like_count',
                'image_url',
                'created_at',
                'updated_at',
            ],
        ]);

});

test('stores a native text type content', function () {
    authUser();
    $data = [
        'type' => 'text',
        'content' => 'Text Type Test',
        'url' => 'http://example.com',
    ];

    $response = $this->postJson('api/natives', $data);

    $response->assertCreated()
        ->assertJsonFragment(['content' => 'Text Type Test']);

    $this->assertDatabaseHas('natives', [
        'type' => 'text',
        'content' => 'Text Type Test',
    ]);

});

test('store a native image type content', function () {
    authUser();
    Storage:fake('public');

    $file = UploadedFile::fake()->image('photo.jps');

    $data = [
        'type' => 'image',
        'image_path' => $file,
    ];
});
