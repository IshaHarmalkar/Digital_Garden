<x-mail::message>
# Weeky Review

Here's your curated review for the week:

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

💗Likes: {{ $native->like_count ?? 0}}

@empty
_No new posts this week.
@endforelse

---

@if($notionPages->isNotEmpty())
## Your Notion Reads

@foreach ($notionPages as $page)
-[{{ $page['title'] }}]({{$page['url']}})    
@endforeach
@else
_No new Notion Reads this week._
@endif


---


That's it for this week. All the best for the new week.<br>
{{ config('app.name') }}
</x-mail::message>
