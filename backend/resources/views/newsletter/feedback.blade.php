@extends('layouts.app')

@section('content')
<div class="feedback-page">
    <h1 class="page-title">Feedback for Newsletter #{{ $newsletter->id }}</h1>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('newsletter.feedback.submit') }}" class="feedback-form">
        @csrf

        @foreach($curated as $item)
            @php
                $model = $item['model'];
                $stats = $model->stats ?? new \App\Models\Stat;
            @endphp

            <div class="feedback-card">
                {{-- Left column: Content --}}
                <div class="feedback-content">
                    <h3 class="content-title">{{ $item['type'] }}</h3>

                    @if($item['type'] === 'Native')
                        <p class="content-text">{{ $model->content }}</p>
                        @if($model->image_url)
                            <img src="{{ $model->image_url }}" alt="Native Content" class="content-image">
                        @endif
                        @if($model->url)
                            <div class="mt-3">
                                <a href="{{ $model->url }}" target="_blank" class="btn-link">Visit Link</a>
                            </div>
                        @endif

                    @elseif($item['type'] === 'Notion')
                        <a href="{{ $model->url }}" target="_blank" class="content-link">
                            {{ $model->title }}
                        </a>

                    @elseif($item['type'] === 'Pinterest')
                        <iframe src="{{ $model->embed_code }}" height="336" width="236" frameborder="0" scrolling="no" class="content-embed"></iframe>
                        <div class="mt-3">
                            📌 <a href="{{ $model->pin_link }}" target="_blank" class="content-link">Go to Pin</a>
                        </div>
                    @endif
                </div>

                {{-- Right column: Feedback --}}
                <div class="feedback-sidebar">
                    <h4 class="sidebar-title">Your Feedback</h4>

                    {{-- Like Button --}}
                    <div class="mb-4">
                        <button type="button"
                                class="like-btn {{ ($stats->like_count ?? 0) > 0 ? 'liked' : '' }}"
                                data-input-id="like-input-{{ $model->id }}">
                            💗 <span>Like</span>
                        </button>
                        <input type="hidden"
                               id="like-input-{{ $model->id }}"
                               name="stats[{{ $item['type'] }}][{{ $model->id }}][like_count]"
                               value="{{ $stats->like_count ?? 0 }}">
                    </div>

                    {{-- See Again --}}
                    <div class="mb-4">
                        <label class="see-again">
                            <input type="checkbox"
                                   name="stats[{{ $item['type'] }}][{{ $model->id }}][see_again_soon]"
                                   value="1" {{ ($stats->see_again_soon ?? false) ? 'checked' : '' }}>
                            <span>❓ See Again Soon</span>
                        </label>
                    </div>

                    <input type="hidden"
                           name="stats[{{ $item['type'] }}][{{ $model->id }}][stat_id]"
                           value="{{ $stats->id }}">
                </div>
            </div>
        @endforeach

        <div class="form-footer">
            <button type="submit" class="btn-submit">Submit Feedback</button>
        </div>
    </form>
</div>

{{-- Inline Script for Like Toggle --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".like-btn").forEach(button => {
        button.addEventListener("click", () => {
            const inputId = button.dataset.inputId;
            const hiddenInput = document.getElementById(inputId);
            const isLiked = hiddenInput.value > 0;

            if (isLiked) {
                hiddenInput.value = 0;
                button.classList.remove("liked");
            } else {
                hiddenInput.value = 1;
                button.classList.add("liked");
            }
        });
    });
});
</script>

{{-- Scoped styles --}}
<style>
.feedback-page {
    width: 100%;
    min-height: 100vh;
    background: #f9fafb;
    padding: 1.5rem;
}

.page-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: #1f2937;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.feedback-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.feedback-card {
    display: flex;
    gap: 1.5rem;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    transition: box-shadow 0.2s ease;
}
.feedback-card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.12);
}

.feedback-content {
    flex: 1;
}
.content-title {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #111827;
}
.content-text {
    margin-bottom: 1rem;
    color: #374151;
}
.content-image {
    border-radius: 0.5rem;
    max-height: 16rem;
    object-fit: cover;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.content-link {
    color: #2563eb;
    text-decoration: underline;
    font-size: 1.125rem;
}
.content-link:hover {
    color: #1d4ed8;
}
.content-embed {
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.feedback-sidebar {
    width: 16rem;
    border-left: 1px solid #e5e7eb;
    padding-left: 1.5rem;
}
.sidebar-title {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #374151;
}

.like-btn {
    width: 100%;
    padding: 0.5rem 1rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    background: #f3f4f6;
    color: #374151;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.25rem;
    transition: all 0.2s ease;
    cursor: pointer;
}
.like-btn:hover {
    background: #e5e7eb;
}
.like-btn.liked {
    background: #ec4899;
    color: #fff;
    border-color: #db2777;
}

.see-again {
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    color: #374151;
}

.form-footer {
    text-align: center;
}
.btn-submit {
    background: #3b82f6;
    color: #fff;
    padding: 0.5rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 500;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: background 0.2s ease;
}
.btn-submit:hover {
    background: #2563eb;
}
.btn-link {
    display: inline-block;
    background: #3b82f6;
    color: #fff;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    text-decoration: none;
    font-size: 0.875rem;
    transition: background 0.2s ease;
}
.btn-link:hover {
    background: #2563eb;
}
</style>
@endsection
