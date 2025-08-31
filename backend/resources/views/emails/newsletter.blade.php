<x-mail::message>
# Weeky Review

Here's your curated review for the week:

---
{{-- Unsplash Image --}}
<div style="text-align: center; margin: 20px 0;">
    <a href="https://unsplash.com/photos/abstract-teal-oval-shape-on-light-blue-background-LqeIetcaGb8">
        <img src="https://i.pinimg.com/150x150/10/10/48/10104839bad015f187e6d1e53652ff81.jpg" 
             alt="Abstract teal oval shape on light blue background" 
             style="max-width: 400px; height: auto; border-radius: 8px; border: 1px solid #ddd;">
    </a>
    <p style="font-size: 12px; color: #666; margin-top: 8px;">
        <a href="https://unsplash.com/photos/abstract-teal-oval-shape-on-light-blue-background-LqeIetcaGb8">View on Unsplash</a>
    </p>
</div>

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
[👍Like]({{ route('newsletter.feedback', ['type' => 'native', 'id' => $native->id, 'action' => 'like'])}})
[🌻 See Again]({{ route('newsletter.feedback', ['type' => 'native', 'id' => $native->id, 'action' =>  'see_again'])}})


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
[👍Like]({{ route('newsletter.feedback', ['type' => 'notion', 'id' => $page['id'], 'action' => 'like'])}})
[🌻 See Again]({{ route('newsletter.feedback', ['type' => 'notion', 'id' => $page['id'], 'action' =>  'see_again'])}})

@endforeach
@else
_No new Notion Reads this week._
@endif


---


That's it for this week. All the best for the new week.<br>
{{ config('app.name') }}
</x-mail::message>
