@extends('layouts.app')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
            <div class="block-account-setting">
                @include('layouts.success')
                <p class="text-uppercase text-puple font-lato-b size-16"> settings </p>
                    {{ Form::open(['route'=>['users.update',$user],'method'=> 'put','class'=>'form-tw margin-top-30']) }}
                    <div class="input-group full-width">
                        <p> Your name </p>
                        <div class="row-info">
                            <input required type="text" name="name" readonly='readonly' value="{{$user->name}}" class="size-12 full-width font-lato-r size-12 form-control">
                            <a href="#" class="text-black" id="edit-name"><i class="size-16 fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <br>
                    <div class="input-group full-width">
                        <p> Email </p>
                        <input required type="email" name="email" readonly='readonly' value="{{$user->email}}" class="full-width font-lato-r size-12 form-control">
                    </div>
                    <br>
                    <div class="input-group full-width">
                        <p> Password</p>
                        <div class="row-info">
                            <input required type="password" value="******" disabled  class="full-width font-lato-r size-12 form-control">
                            <a href="#" class="text-black" id="edit-pass" data-toggle="modal" data-target="#modal-change-pass"><i class="size-16 fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <div class="margin-top-30">
                        <p>Customer Code or Customer Name ?</p>
                        <label class="control control--radio">Customer Name
                            <input type="radio" name="type_customized" value="0" {{!$user->type_customized ? 'checked' : ''}}>
                            <div class="control__indicator"></div>
                        </label>
                        <label class="control control--radio">Customer Code
                            <input type="radio" name="type_customized" value="1" {{$user->type_customized ? 'checked' : ''}}>
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div class="margin-top-30">
                        <p>Auto Email Reminders </p>
                        <label class="control control--radio">Yes
                            <input type="radio" name="email_reminder" value="1"  {{$user->email_reminder ? 'checked' : ''}}>
                            <div class="control__indicator"></div>
                        </label>
                        <label class="control control--radio">No
                            <input type="radio" name="email_reminder" value="0"  {{!$user->email_reminder ? 'checked' : ''}}>
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <br>
                    <p class="text-puple-l"><em>Receive Automatic Email, Reminding</em></p>
                    <p class="text-puple-l"><em>Updating of clients Risk Assesment.</em></p>
                    <br>
                    <div class="input-group full-width">
                        <p> Country</p>
                        {{ Form::select('country_id', $countries, $user->country_id,['class'=> 'full-width','disabled'])}}
                    </div>
                    <div class="margin-top-30">
                        <p>Currency </p>
                        @foreach(config('currency.symbol') as $key => $symbol)
                            <label class="control control--radio">{{$symbol}}
                                <input type="radio" name="currency" value="{{$key}}" {{$user->currency == $key  ? 'checked' : 'disabled="true"' }}>
                                <div class="control__indicator"></div>
                            </label>
                        @endforeach

                    </div>
                    <input type="submit" value="Save change" class="btn-tw-puple margin-top-30">
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal change password-->
<div id="modal-change-pass" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <h4 class="font-lato-b text-puple text-uppercase"> Change password </h4>
                <br>
                {{ Form::open(['route'=>['users.update',$user],'method'=> 'put','id'=>'ajaxPasswordForm']) }}
                    <div class="input-group full-width">
                        <p class="size-12"> Enter Current Password</p>
                        <input required name="old_password" type="password" class="font-lato-r size-12 form-control">
                    </div>
                    <br>
                    <div class="input-group full-width">
                        <p class="size-12"> Enter New Password </p>
                        <input required name="password" type="password" class="font-lato-r size-12 form-control">
                    </div>
                    <br>
                    <div class="input-group full-width">
                        <p class="size-12"> Re-enter New Password </p>
                        <input required name="password_confirm" type="password" class="font-lato-r size-12 form-control">
                    </div>
                    <input type="button" class="btn-tw cancel margin-top-30" data-dismiss="modal" value="Cancel">
                    <input type="submit" value="Save change" class="btn-tw btn-tw-puple margin-top-30">
                {{Form::close()}}
            </div>
        </div>
    </div>
</div>
@endsection