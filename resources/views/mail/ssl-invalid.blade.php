@component('mail::message')
# 🔒 Invalid SSL on:  {{ $website->url }}

Please advise the account manager to resolve.

@component('mail::button', ['url' => $website->show_link])
    Open Monitor
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
