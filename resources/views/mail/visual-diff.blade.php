@component('mail::message')
# A difference has been found on:

<a href="{{ $scan->url }}">{{ $scan->url }}</a> at  {{ $scan->created_at->format('d/m/Y H:i:s') }}

## Before
<img style="margin: 20px 0;" src="{{ $scan->comparedWith->screenshot_url }}" alt="Screenshot from the previous scan.">

## After
<img style="margin: 20px 0;" src="{{ $scan->screenshot_url }}" alt="Screenshot from the last scan.">

## Differences
<img style="margin: 20px 0;" src="{{ $scan->diff_url }}" alt="Heat map of differences.">

Thanks,<br>
{{ config('app.name') }}
@endcomponent
