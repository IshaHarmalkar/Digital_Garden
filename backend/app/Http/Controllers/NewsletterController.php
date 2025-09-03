<?php

namespace App\Http\Controllers;

use App\Models\Native;
use App\Models\NotionContent;
use App\Models\PinterestContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsletterController extends Controller
{
    public function show($newsletterId)
    {
        return view('newsletter.feedback', compact('newsletterId'));
    }

    public function handleFeedback(Request $request, $newsletterId)
    {
        $request->validate([
            'content_type' => 'required|in:native,notion,pinterest',
            'content_id' => 'required|integer',
            'action' => 'required|in:like,see_again',
        ]);

        $contentType = $request->content_type;
        $contentId = $request->content_id;
        $action = $request->action;

        try {
            DB::transaction(function () use ($contentType, $contentId, $action) {
                $model = $this->getModel($contentType, $contentId);

                if (! $model) {
                    throw new \Exception('Content not found');
                }

                $stats = $model->stats()->firstOrCreate([
                    'statable_type' => get_class($model),
                    'statable_id' => $model->id,
                ], [
                    'like_count' => 0,
                    'see_again_soon' => false,
                ]);

                if ($action === 'like') {
                    $stats->increment('like_count');
                } elseif ($action === 'see_again') {
                    $stats->update(['see_again_soon' => true]);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Feedback recorded successfully!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to record feedback: '.$e->getMessage(),
            ], 500);
        }
    }

    private function getModel($contentType, $contentId)
    {
        switch ($contentType) {
            case 'native':
                return Native::find($contentId);
            case 'notion':
                return NotionContent::find($contentId);
            case 'pinterest':
                return PinterestContent::find($contentId);
            default:
                return null;
        }
    }
}
