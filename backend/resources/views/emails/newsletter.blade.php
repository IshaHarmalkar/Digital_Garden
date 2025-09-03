<x-mail::message>

# Weeky Review

Here's your curated review for the week:






---


{{-- Pinterest Pins --}}
<div>
  
</div>
@if($pins->isNotEmpty())
## Your Pinterest Inspiration
@foreach ($pins as $pin)
<div style="text-align: center; margin: 20px 0;">

 
  {{-- mail/pins.blade.php --}}
@foreach ($pins as $pin)
<div style="text-align: center; margin: 20px 0;">
    <a href="{{ $pin['link'] }}" target="_blank">
        <img src="{{ $pin['image'] }}" alt="Pinterest Pin" style="max-width:100%; border-radius: 8px;" />
    </a>
    <div style="margin-top: 10px;">
        📌 <a href="{{ $pin['link'] }}" target="_blank">Go to Pin</a>
    </div>
    <div style="margin-top: 10px;">💗 Likes: {{ $pin['like_count'] }}</div>
</div>
@endforeach




</div>
💗Likes: {{ $pin['like_count'] }}
{{-- feedback links --}}


---

@endforeach
@else
*No new Pinterest pins this week.*
@endif

---

{{--Native Items --}}

@forelse($items as $native)
---

@if($native->type === 'text')

{{ $native->content}}
@endif

@if($native->type === 'image')

![Native image]({{ $native->image_url}})
@endif


@if($native->url)

<x-mail::button :url="$native->url">
Visit Link
</x-mail::button>
@endif

💗Likes: {{ $native->stats->like_count ?? 0}}
❓See Again: {{ $native->stats->see_again_soon ? 'Yes' : 'No'}}

{{-- feedback links --}}



@empty
_No new posts this week.
@endforelse

---

@if($notionPages->isNotEmpty())
## Your Notion Reads

@foreach ($notionPages as $page)
-[{{ $page['title'] }}]({{$page['url']}})    
💗Likes: {{$page['like_count']}}
❓See Again: {{ $page['see_again_soon'] ? 'Yes' : 'No'}}

{{-- feedback links --}}

@endforeach
@else
_No new Notion Reads this week._
@endif


---


That's it for this week. All the best for the new week.<br>

## 📝 Share Your Feedback

Your feedback helps us improve future newsletters:

<x-mail::button :url="route('newsletter.feedback', $newsletterId)">
Rate This Week's Content
</x-mail::button>

<br>
{{ config('app.name') }}
</x-mail::message>
