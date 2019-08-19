@component('mail::message')
# DNS change detected on:  {{ $website->url }}

The public DNS file has changed since the last scan - please check and make any necessary changes.

Please see the diff below:

<div style="padding: 20px; background: #f1f2fb;">
<pre style="margin:0;padding:0;"><code style="margin:0;padding:0;">{{ $scan->diff }}</code></pre>
</div>

@component('mail::button', ['url' => $website->show_link])
Open Monitor
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
