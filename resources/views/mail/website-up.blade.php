@component('mail::message')
# Website Back Online: {{ $website->url }}

The website had been offline for {{ $website->time_spent_offline }}.

@component('mail::button', ['url' => $website->show_link])
    Open Monitor
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
