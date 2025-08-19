<?php

namespace App\Services;

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
            $pages = Notion::database($databaseId)->query()->asCollection();

            $simplifiedPages = [];
            foreach ($pages as $page) {

                $title = $page->getTitle();

                $simplifiedPages[] = [
                    'id' => $page->getId(),
                    'title' => $title,
                ];
            }

            return $simplifiedPages;

        } catch (\Exception $e) {
            Log::error('Notion API Error: '.$e->getMessage());

            return ['error' => 'Could not fetch pages from Notion.'];
        }
    }
}
