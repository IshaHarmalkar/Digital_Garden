<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Embed Test</title>
</head>
<body>
    <h1>Testing Pinterest Embed</h1>

    <p>Here’s a static embed code:</p>
    <div class="pinterest-embed">
        <iframe 
            src="https://assets.pinterest.com/ext/embed.html?id=1618549864245940" 
            height="336" 
            width="236" 
            frameborder="0" 
            scrolling="no">
        </iframe>
    </div>

    <hr>

    <p>Here’s the same thing pulled from a variable:</p>
    @php
        $embed = '<iframe src="https://assets.pinterest.com/ext/embed.html?id=1618549864245940" height="336" width="236" frameborder="0" scrolling="no"></iframe>';
    @endphp

    {!! $embed !!}
</body>
</html>
