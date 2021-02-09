@component('mail::message')
# Browser message detected on:  {{ $website->url }}

The following URL (<a href="{{ $page->url }}">{{ $page->url }}</a>) flagged up a console warning, please investigate.

Please see the output below:

<div style="padding: 20px; background: #f1f2fb;">
<pre style="margin:0;padding:0;"><code style="margin:0;padding:0;">{{ $page->messages ?? $page->exception ?? $page->response }}</code></pre>
</div>

@component('mail::button', ['url' => $website->show_link])
Open Monitor
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
