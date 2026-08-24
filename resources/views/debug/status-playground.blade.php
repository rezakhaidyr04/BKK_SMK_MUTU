<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Status Playground</title>
    <style>
        body { font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; padding: 24px; }
        .row { display:flex; align-items:center; gap:12px; margin:8px 0; }
        .key { width:220px; color:#374151; font-weight:600 }
    </style>
</head>
<body>
    <h1>Status Playground</h1>
    <p>Preview how `status-badge` renders different status values.</p>

    @foreach($statuses as $s)
        <div class="row">
            <div class="key">{{ $s }}</div>
            <div>
                <x-ui.status-badge :status="$s" />
            </div>
        </div>
    @endforeach

    <p style="margin-top:24px; color:#6b7280">Note: this route is intended for local/dev preview only.</p>
</body>
</html>
