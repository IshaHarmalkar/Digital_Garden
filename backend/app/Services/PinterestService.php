<?php

namespace App\Services;

use App\Models\PinterestContent;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PinterestService
{
    protected string $baseUrl;

    protected string $token;

    public function __construct()
    {
        $this->baseUrl = config('services.pinterest.base_url');
        $this->token = config('services.pinterest.access_token');
    }

    // fetch pins  for a board
    // supports both full syn -> intially fetch and newly added fetch.
    public function fetchAndStorePinsForBoard(string $boardId): array
    {
        try {

            $latestStored = PinterestContent::where('board_id', $boardId)->max('created_at');

            $url = "{$this->baseUrl}/boards/{$boardId}/pins";
            $params = ['page_size' => 200];
            $totalSaved = 0;

            do {
                $response = Http::withToken($this->token)
                    ->get($url, $params);

                Log::info('Pinterest API Response (Pins)', [
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);

                if ($response->failed()) {
                    Log::error('Pinterest API Error (Pins)', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);

                    return ['error' => 'failed to fetch pins'];
                }

                $data = $response->json();
                $pins = $data['items'] ?? [];

                foreach ($pins as $pin) {

                    // stop if we reach old pins
                    if ($latestStored && $pin['created_at'] <= $latestStored) {

                        Log::info('reached already stored pins, stopping sync.', [

                            'pin_id' => $pin['id'],
                            'latestStored' => $latestStored,
                        ]);

                        return [
                            'board_id' => $boardId,
                            'pins_saved' => $totalSaved,
                            'stopped_at' => $pin['created_at'],
                        ];

                    }

                    $src = null;
                    $images = $pin['media']['images'] ?? [];
                    // LOG THE DEBUGGING INFO
                    Log::info('Processing Pin', [
                        'pin_id' => $pin['id'],
                        'available_image_sizes' => array_keys($images),
                    ]);

                    if (isset($images['600x']['url'])) {
                        $src = $images['600x']['url'];
                    } elseif (isset($images['400x300']['url'])) {
                        $src = $images['400x300']['url'];
                    } elseif (isset($images['150x150']['url'])) {
                        $src = $images['150x150']['url'];
                    } elseif (isset($images['1200x']['url'])) {
                        $src = $images['1200x']['url'];
                    }
                    Log::info('Raw src before parsing', ['src' => $src]);
                    if ($src) {
                        $src = parse_url($src, PHP_URL_PATH);
                        $src = ltrim($src, '/');
                    }

                    Log::info('Final src being saved', [
                        'pin_id' => $pin['id'],
                        'src' => $src,
                    ]);

                    PinterestContent::updateOrCreate(
                        [
                            'pin_id' => $pin['id'],
                            'board_id' => $boardId,

                        ],
                        [
                            'src' => $src,

                        ]
                    );

                    $totalSaved++;

                }
                $bookmark = $data['bookmark'] ?? null;
                $params = ['page_size' => 200, 'bookmark' => $bookmark];
            } while (! empty($bookmark));

            return [
                'board_id' => $boardId,
                'pins_saved' => $totalSaved,
                'full_sync' => $latestStored ? false : true,
            ];

        } catch (\Exception $e) {
            Log::error('Pinterest API Exception: '.$e->getMessage());

            return ['error' => 'Pinterest API call failed.'];
        }
    }

    public function fetchPinSrc(string $pinId): array
    {
        try {
            $url = "{$this->baseUrl}/pins/{$pinId}";
            $response = Http::withToken($this->token)->get($url);

            Log::info('Pinterest API Debug', [
                'url' => $url,
                'status' => $response->status(),
                'body' => $response->body(),
                'headers' => $response->headers(),
                'json' => $response->json(),
            ]);

            // log full response for debugging
            Log::info('Pinterest API Response (Pin Details )', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            if ($response->failed()) {

                Log::error('Pinterest API Error (Pin Details)', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return ['error' => 'failed to fetch pin details'];
            }

            $data = $response->json();

            // fetch media array
            $media = $data['media'] ?? [];
            $imageUrl = null;

            // try to get 600 at index 2, or fall abck to 1 or 0
            if (isset($media[2]['url'])) {
                $imageUrl = $media[2]['url'];
            } elseif (isset($media[1]['url'])) {
                $imageUrl = $media[1]['url'];
            } elseif (isset($media[0]['url'])) {
                $imageUrl = $media[0]['url'];
            }

            $pinLink = "https://wwww.pinterest.com/pin/{$pinId}/";

            return [
                'pin_Id' => $pinId,
                'image_url' => $imageUrl,
                'pin_link' => $pinLink,
            ];

        } catch (\Exception $e) {
            Log::error('Pinterest API Exception (Pin Details): '.$e->getMessage());

            return ['error' => 'Pinterest API call failed'];
        }
    }
}
