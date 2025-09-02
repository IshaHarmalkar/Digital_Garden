<?php

use App\Mail\WeeklyNewsletterMail;
use App\Services\NotionService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/preview/newsletter', function () {
    return (new WeeklyNewsletterMail)->render();
});

Route::get('/test-page', function () {
    return view('test');
});

Route::get('/notion-database-details', function (NotionService $notionService) {
    $databaseId = env('NOTION_DATABASE_ID');
    $data = $notionService->getDatabaseDetails($databaseId);

    if (isset($data['error'])) {
        return response()->json($data, 500);
    }

    return response()->json($data);
});

Route::get('/newsletter-feedback', [NewsletterController::class, 'feedback'])
    ->name('newsletter.feedback');

Route::get('/notion-pages', function (NotionService $notionService) {
    $databaseId = env('NOTION_DATABASE_ID');
    $data = $notionService->getPagesFromDatabase($databaseId);

    if (isset($data['error'])) {
        return response()->json($data, 500);
    }

    return response()->json($data);
});

require __DIR__.'/auth.php';
