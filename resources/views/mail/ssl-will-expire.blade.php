@component('mail::message')
# The SSL is expiring this week on:  {{ $website->url }}

The SSL will expire in {{ now()->diffAsCarbonInterval($scan->valid_to)->forHumans(['join' => true]) }}

Please liaise with the account manager to resolve.

@component('mail::button', ['url' => $website->show_link])
    Open Monitor
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
