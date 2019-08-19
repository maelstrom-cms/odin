@component('mail::message')
# Weak SSL found on:  {{ $website->url }}

Anything less than a <strong>Grade B</strong> SSL is considered weak and will get flagged.

Current rating is: <strong>{{ $scan->grade }}</strong>

@component('mail::button', ['url' => $website->show_link])
    Open Monitor
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
