<?php

namespace App\Services;

use FiveamCode\LaravelNotionApi\Query\StartCursor;
use Illuminate\Support\Facades\Log;
use Notion;

class NotionService
{
    public function getDatabaseDetails(string $databaseId): array
    {
        try {
            $database = Notion::databases()->find($databaseId);

            // You can access various properties of the database object
            $title = $database->getTitle();
            $properties = $database->getProperties();
            $url = $database->getUrl();

            return [
                'title' => $title,
                'url' => $url,
                'properties' => $properties,
            ];

        } catch (\Exception $e) {
            Log::error('Notion API Error: '.$e->getMessage());

            return ['error' => 'Could not fetch database details from Notion.'];
        }
    }

    /**
     * Retrieves the pages from a Notion database,
     * returning only the page ID and title.
     */
    public function getPagesFromDatabase(string $databaseId): array
    {
        try {

            $simplifiedPages = [];
            $startCursor = null;

            do {

                $query = Notion::database($databaseId);

                if ($startCursor) {

                    $query->offset(new StartCursor($startCursor));

                }

                $response = $query->query();

                // process current batch of pages

                foreach ($response->asCollection() as $page) {
                    $simplifiedPages[] = [
                        'id' => $page->getId(),
                        'title' => $page->getTitle(),
                        'last_edited_time' => $page->getLastEditedTime()?->format('Y-m-d H:i:s'),

                    ];
                }

                // get next cursor

                $startCursor = null;

                if (method_exists($response, 'getNextCursor')) {
                    $startCursor = $response->getNextCursor();
                    Log::info('Using getNextCursor, cursor: '.($startCursor ?? 'null'));
                } elseif (method_exists($response, 'getRawNextCursor')) {
                    $rawCursor = $response->getRawNextCursor();
                    $startCursor = $rawCursor ? $rawCursor : null;

                    Log::info('Using getRawNextCursor, cursor: '.($startCursor ?? 'null'));

                } elseif (method_exists($response, 'hasMore') && $response->hasMore()) {

                    // of we can't get cursor but htere are more pages, break to avoid iniffnite
                    Log::warning('More pages available but cannot get next cursor');
                    break;
                }

                if (! $startCursor) {
                    Log::info('No more cursor found, stopping pagination.');
                    break;
                }

            } while (true);

            return $simplifiedPages;

        } catch (\Exception $e) {
            Log::error('Notion API Error: ', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            // detailed error logs
            return ['error' => 'Could not fetch pages from Notion.'.$e->getMessage()];
        }
    }
}
