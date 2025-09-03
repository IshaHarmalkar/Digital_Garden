<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Newsletter Feedback</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .feedback-section {
            margin-bottom: 25px;
            padding: 20px;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            background-color: #f8f9fa;
        }
        .feedback-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-like {
            background-color: #dc3545;
            color: white;
        }
        .btn-like:hover {
            background-color: #c82333;
        }
        .btn-see-again {
            background-color: #28a745;
            color: white;
        }
        .btn-see-again:hover {
            background-color: #218838;
        }
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .success-message {
            color: #28a745;
            font-weight: bold;
            margin-top: 10px;
        }
        .error-message {
            color: #dc3545;
            font-weight: bold;
            margin-top: 10px;
        }
        .thank-you {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            background-color: #d4edda;
            border-radius: 6px;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 Newsletter Feedback</h1>
            <p>Help us improve by sharing your thoughts on this week's content!</p>
        </div>

        @php
            $newsletter = \App\Models\Newsletter::find($newsletterId);
            $curatedItems = collect($newsletter->curated_items ?? []);
        @endphp

        @foreach($curatedItems as $item)
            <div class="feedback-section" data-content-type="{{ strtolower($item['type']) }}" data-content-id="{{ $item['id'] }}">
                @if($item['type'] === 'Native')
                    @php
                        $native = \App\Models\Native::find($item['id']);
                    @endphp
                    @if($native)
                        <h3>📝 Native Content</h3>
                        @if($native->type === 'text')
                            <p>{{ Str::limit($native->content, 100) }}</p>
                        @elseif($native->type === 'image')
                            <p>🖼️ Image Content</p>
                        @endif
                    @endif

                @elseif($item['type'] === 'Notion')
                    @php
                        $notion = \App\Models\NotionContent::find($item['id']);
                    @endphp
                    @if($notion)
                        <h3>📖 {{ $notion->title }}</h3>
                        <p>Notion page content</p>
                    @endif

                @elseif($item['type'] === 'Pinterest')
                    @php
                        $pinterest = \App\Models\PinterestContent::find($item['id']);
                    @endphp
                    @if($pinterest)
                        <h3>📌 Pinterest Pin</h3>
                        <p>Pinterest inspiration content</p>
                    @endif
                @endif

                <div class="feedback-actions">
                    <button class="btn btn-like" onclick="submitFeedback('{{ strtolower($item['type']) }}', {{ $item['id'] }}, 'like', this)">
                        ❤️ Like This
                    </button>
                    <button class="btn btn-see-again" onclick="submitFeedback('{{ strtolower($item['type']) }}', {{ $item['id'] }}, 'see_again', this)">
                        🔄 See Again Soon
                    </button>
                </div>
                
                <div class="feedback-message"></div>
            </div>
        @endforeach

        <div class="thank-you" style="display: none;" id="thank-you-message">
            <h3>🙏 Thank you!</h3>
            <p>Your feedback helps us curate better content for you.</p>
        </div>
    </div>

    <script>
        let feedbackCount = 0;
        const totalItems = {{ $curatedItems->count() }};

        async function submitFeedback(contentType, contentId, action, button) {
            const section = button.closest('.feedback-section');
            const messageDiv = section.querySelector('.feedback-message');
            
            // Disable buttons
            const buttons = section.querySelectorAll('.btn');
            buttons.forEach(btn => btn.disabled = true);
            
            try {
                const response = await fetch(`/newsletter-feedback/{{ $newsletterId }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        content_type: contentType,
                        content_id: contentId,
                        action: action
                    })
                });

                const data = await response.json();

                if (data.success) {
                    messageDiv.innerHTML = '<div class="success-message">✅ ' + data.message + '</div>';
                    feedbackCount++;
                    
                    // Show thank you message if all feedback is submitted
                    if (feedbackCount >= totalItems) {
                        document.getElementById('thank-you-message').style.display = 'block';
                    }
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                messageDiv.innerHTML = '<div class="error-message">❌ ' + error.message + '</div>';
                // Re-enable buttons on error
                buttons.forEach(btn => btn.disabled = false);
            }
        }
    </script>
</body>
</html>