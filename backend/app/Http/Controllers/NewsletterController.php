<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    //

    public function feedback(Request $request)
    {
        $type = $request->get('type');
        $id = $request->get('id');
        $action = $request->get('action');

        $model = $type === 'native' ? \App\Models\Native::find($id) : \App\Models\NotionContent::find($id);

        if ($model) {
            $stats = $model->stats()->firstOrCreate([]);

            if ($action === 'like') {
                $stats->increment('like_count');
            }

            if ($action === 'see_again') {
                $stats->update(['see_again_soon' => true]);
            }
        }

        return redirect()->away(config('app.url')); // or "Thanks for feedback" page
    }
}
