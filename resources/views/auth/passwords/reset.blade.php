@extends('maelstrom::layouts.wrapper')

@push('head_after')
    <style> body { background: #f7fafc; }</style>
@endpush

@section('title')
    Set New Password
@endsection

@section('main')
    <div data-aos="fade-in" data-aos-delay="100" data-aos-duration="1000" class="w-full max-w-xs mx-auto mt-24">

        @include('maelstrom::components.loader')

        @if (session()->has('status'))
            <div class="pb-10">
                @include('maelstrom::components.alert', ['message' => session()->get('status')])
            </div>
        @else
            <div class="pb-10">
                @include('maelstrom::components.alert', ['message' => 'You can now reset your password below'])
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" class="cloak bg-white shadow-md rounded px-8 pt-6 mb-4">
            @csrf()

            <input type="hidden" name="token" value="{{ $token }}">

            @include('maelstrom::inputs.text', [
                'name' => 'email',
                'label' => 'Email Address',
                'html_type' => 'email',
                'required' => true,
                'default' => $email ?? null,
            ])

            @include('maelstrom::inputs.secret', [
                'name' => 'password',
                'label' => 'New Password',
                'required' => true,
            ])

            @include('maelstrom::inputs.secret', [
                'name' => 'password_confirmation',
                'label' => 'Confirm Password',
                'required' => true,
            ])

            <div class="mt-10 flex justify-between items-center">
                @include('maelstrom::buttons.button', [
                    'label' => 'Change Password',
                    'type' => 'primary',
                ])
            </div>
        </form>

        <p class="mt-10 text-center text-gray-500 text-xs">
            &copy;{{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </p>

    </div>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
