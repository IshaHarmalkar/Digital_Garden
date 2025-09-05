<?php

namespace App\Http\Controllers;

use App\Models\Native;
use App\Models\Newsletter;
use App\Models\NotionContent;
use App\Models\PinterestContent;
use App\Models\Stat;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function showFeedback($newsletterId)
    {
        $newsletter = Newsletter::findOrFail($newsletterId);

        $hydrated = collect($newsletter->curated_items)->map(function ($item) {
            switch ($item['type']) {
                case 'Native':
                    return [
                        'type' => 'Native',
                        'model' => Native::with('stats')->find($item['id']),
                    ];
                case 'Notion':
                    return [
                        'type' => 'Notion',
                        'model' => NotionContent::with('stats')->find($item['id']),
                    ];
                case 'Pinterest':
                    return [
                        'type' => 'Pinterest',
                        'model' => PinterestContent::with('stats')->find($item['id']),
                    ];
            }
        })->filter();

        return view('newsletter.feedback', [
            'curated' => $hydrated,
            'newsletter' => $newsletter,
        ]);
    }

    public function submitFeedback(Request $request)
    {
        $statsInput = $request->input('stats', []);

        foreach ($statsInput as $type => $items) {
            foreach ($items as $id => $statData) {
                $statId = $statData['stat_id'] ?? null;

                if ($statId) {
                    // Update existing stat
                    Stat::where('id', $statId)->update([
                        'like_count' => $statData['like_count'] ?? 0,
                        'see_again_soon' => isset($statData['see_again_soon']),
                    ]);
                } else {
                    // Create new stat if none exists
                    $modelClass = match ($type) {
                        'Native' => Native::class,
                        'Notion' => NotionContent::class,
                        'Pinterest' => PinterestContent::class,
                    };

                    $model = $modelClass::find($id);
                    if ($model) {
                        $model->stats()->create([
                            'like_count' => $statData['like_count'] ?? 0,
                            'see_again_soon' => isset($statData['see_again_soon']),
                        ]);
                    }
                }
            }
        }

        return back()->with('success', 'Feedback saved!');
    }
}
