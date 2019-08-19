@component('mail::message')
# The SSL has expired on:  {{ $website->url }}

SSL expired {{ now()->diffAsCarbonInterval($scan->valid_to)->forHumans(['join' => true]) }} ago.

Please advise the account manager to resolve.

@component('mail::button', ['url' => $website->show_link])
    Open Monitor
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
