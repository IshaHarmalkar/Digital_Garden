<x-mail::message>
# Weeky Review

Here's your curated review for the week:

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

## Your Notion Reads

@foreach ($notionPages as $page)
-[{{ $page['title'] }}]({{$page['url']}})    
@endforeach

---


That's it for this week. All the best for the new week.<br>
{{ config('app.name') }}
</x-mail::message>
