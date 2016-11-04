@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
                <div class="block-login">
                    <p class="text-uppercase text-puple font-lato-b size-16">change password</p>
                    <form class="form-tw form-tw-1 margin-top-30" role="form" method="POST"
                          action="{{ route('postChangePassword') }}">
                        {{ csrf_field() }}
                        <div class="input-group full-width">
                            <p> Enter Current password</p>
                            <input required type="password" class="full-width font-lato-r size-12 form-control"
                                   name="old_password">
                        </div>
                        @if ($errors->has('old_password'))
                            <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('old_password') }}</strong>
                                    </span>
                        @endif
                        @if(session()->has('message'))

                            <span class="help-block">
                                        <strong class="text-danger"> {{ session()->get('message') }}</strong>
                                    </span>
                        @endif
                        <br>
                        <div class="input-group full-width">
                            <p> Enter new password</p>
                            <input required type="password" class="full-width font-lato-r size-12 form-control"
                                   name="password">
                        </div>
                        @if ($errors->has('password'))
                            <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('password') }}</strong>
                                    </span>
                        @endif
                        <br>
                        <div class="input-group full-width">
                            <p> Re-enter new password</p>
                            <input required type="password" class="full-width font-lato-r size-12 form-control"
                                   name="password_confirm">
                        </div>
                        @if ($errors->has('password_confirm'))
                            <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('password_confirm') }}</strong>
                                    </span>
                        @endif
                        <input type="submit" value="Change Password" class="btn-tw-puple margin-top-30">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
