@extends('maelstrom::layouts.index')

@section('buttons')
    @include('maelstrom::buttons.button', [
        'url' => route('websites.create'),
        'label' => 'Add Website'
    ])
@endsection
