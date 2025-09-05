<?php

use App\Http\Controllers\NewsletterController;
use App\Mail\WeeklyNewsletterMail;
use App\Services\NotionService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/preview/newsletter', function () {
    return (new WeeklyNewsletterMail)->render();
});

// Newsletter feedback routes
Route::get('/newsletter/{newsletter}/feedback', [NewsletterController::class, 'showFeedback'])
    ->name('newsletter.feedback.show');

Route::post('/newsletter/feedback', [NewsletterController::class, 'submitFeedback'])
    ->name('newsletter.feedback.submit');

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

Route::get('/notion-pages', function (NotionService $notionService) {
    $databaseId = env('NOTION_DATABASE_ID');
    $data = $notionService->getPagesFromDatabase($databaseId);

    if (isset($data['error'])) {
        return response()->json($data, 500);
    }

    return response()->json($data);
});

require __DIR__.'/auth.php';
