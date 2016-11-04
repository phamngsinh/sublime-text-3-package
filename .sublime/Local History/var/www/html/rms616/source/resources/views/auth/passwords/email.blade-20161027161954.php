@extends('layouts.app')

        <!-- Main Content -->
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
                <div class="block-login">
                    <p class=" text-puple font-lato-b size-16"><span class="text-uppercase">Reset password </span><a
                                href="{{url('/login')}}" class="pull-right size-12">Return to Login </a></p>
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form class="form-tw form-tw-1 margin-top-30" role="form" method="POST"  action="{{ url('/password/email') }}">
                        {{ csrf_field() }}
                        <div class="input-group full-width {{ $errors->has('email') ? ' has-error' : '' }}">
                            <p> Email address</p>
                            <input required id="email" type="email" class="full-width font-lato-r size-12 form-control"
                                   name="email" value="{{ $email or old('email') }}">

                        </div>
                        @if ($errors->has('email'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                        @endif
                        @include('layouts.errors_messages')
                        <input type="submit" value="Send" class="btn-tw-puple margin-top-30">
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
