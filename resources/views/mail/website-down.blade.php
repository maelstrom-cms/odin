@component('mail::message')
# Website Offline: {{ $website->url }}

The above website appears to be offline.

We could not find the defined keyword of <strong>"{{ $website->uptime_keyword }}"</strong> on the page.

@component('mail::button', ['url' => $website->show_link])
    Open Monitor
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
