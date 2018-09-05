@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"></div>

                <div class="card-body">
                    <form method="POST" action="{{ route('long-url') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Enter URL:') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('long_url') ? ' is-invalid' : '' }}" name="long_url" value="{{ old('long_url') }}" required autofocus>

                                @if ($errors->has('long_url'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('long_url') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        @if(session('url'))
                            <div class="col-md-6 offset-md-4">
                                    <a href="{{ env('APP_URL') . '/' . session('url')->short_url }}">short link</a>
                            </div>
                        @endif
                        @if(session('errors'))
                            <div class="col-md-6 offset-md-4">
                               {{ session('errors')->first() }}
                            </div>
                        @endif
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send') }}
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
