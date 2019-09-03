@extends('maelstrom::layouts.form')

@section('content')

    @component('maelstrom::components.form', [
       'action' => $action,
       'method' => $method,
   ])

        @include('websites-form', ['entry' => $entry])

    @endcomponent

@endsection
