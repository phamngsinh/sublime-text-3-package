@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row ">
            <div class="tab-content">
                <div role="tabpanel" id="step1"
                     class="tab-pane fade in active col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
                    <div class="block-buy">
                        <div class="step step-1">
                            <img src="{{url('images/step-1.png')}}" class="full-width">

                            <p class="margin-top-10">
                                <span class="size-12 text-left">Select Subscription</span>
                                <span class="size-12 text-center">Payment</span>
                                <span class="size-12 text-right">Success</span>
                            </p>
                        </div>
                        <p class="margin-top-45 text-uppercase text-puple font-lato-b size-16"> Buy Subscription </p>

                        <form class="form-tw  margin-top-30">
                            <div class="margin-top-20">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <p class="font-lato-b size-14">Your name </p>
                                    </div>
                                    <div class="col-xs-8">
                                        <p class="text-puple-l"><em>{{$user->name}} </em></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-4">
                                        <p class="font-lato-b size-14">Email </p>
                                    </div>
                                    <div class="col-xs-8">
                                        <p class="text-puple-l"><em>{{$user->email}} </em></p>
                                    </div>
                                </div>
                            </div>
                            <br>

                            <div class="input-group full-width">
                                <p> Choose your Subscription</p>
                                {{Form::select('subscription_package_select',
                                    \App\Models\SubscriptionPackage::getAll()->lists('name','id'),null,['class'=>'full-width','id'=>'subscription_package'])}}
                            </div>
                            <br>

                            <div class="input-group full-width">
                                <p> Expiry date </p>
                                <input type="text" readonly="readonly" value="{{$expired->format('d/m/Y')}}"
                                       class="size-12 full-width font-lato-r size-12 form-control subscription_package_expired">

                            </div>
                            <br>

                            <div class="input-group full-width">
                                <p> Total Amount </p>
                                <input type="text" readonly="readonly" value="{{render_price($package->price)}}"
                                       class="size-12 full-width font-lato-r size-12 form-control subscription_package_amount">

                            </div>
                            <input type="button" value="Buy subscription" class="btn-tw-puple margin-top-30"
                                   id="buy_next">
                        </form>
                    </div>

                </div>
                <div role="tabpanel" id="step2" class="tab-pane fade col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
                    <div class="block-buy">
                        <div class="step step-2">
                            <img src="{{url('images/step-2.png')}}" class="full-width">
                            <p class="margin-top-10">
                                <span class="size-12 text-left">Select Subscription</span>
                                <span class="size-12 text-center">Payment</span>
                                <span class="size-12 text-right">Success</span>
                            </p>
                        </div>
                        <form class="form-tw  margin-top-30" action="{{route('subscriptions.store')}}" method="post">
                            {{csrf_field()}}
                            <input type="hidden" name="expiration" id="subscription_package_expired"
                                   value="{{$expired->timestamp}}">
                            <input type="hidden" name="total" id='subscription_package_amount'
                                   value="{{$package->price}}">
                            <input type="hidden" name="subscription_package_id" id='subscription_package_id'
                                   value="{{$package->id}}">
                            <p class="margin-top-45 text-uppercase  font-lato-b size-16"><span
                                        class="text-puple"> Total amount: </span><span
                                        class="text-red pull-right subscription_package_amount_total">{{render_total($package->price)}}</span>
                            </p>
                            <hr>
                            <div class="margin-top-30 get-payment">
                                <p class="size-14 margin-bottom-10">Select Payment Method</p>
                                <label class="control control--radio"><img src="{{url('images/payment-visa.png')}}"/>
                                    <input type="radio" name="payment" value="paypal" checked>
                                    <div class="control__indicator"></div>
                                </label>
                                <label class="control control--radio pull-right"><img
                                            src="{{url('images/payment-master.png')}}"/>
                                    <input type="radio" name="payment" value="paypal">
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                            <br><br>

                            <button class="btn-tw-puple btn-tw grey text-center" type="button" id="buy_back">Back
                            </button>
                            <button type="submit" class="btn-tw-puple btn-tw text-center" id="buy_proceed">Proceed
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection