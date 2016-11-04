<!DOCTYPE html>
<html>
<head>
    <title>Risk24.org</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Kiyoshi-lamnv_qsoft VietNam">
    <link rel="icon" type="image/png" href="http://creativelybeba.com/wp-content/uploads/2011/09/twitter-bird.jpg">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" type="text/css" href="{{url('css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{url('css/owl-carousel/owl.carousel.css')}}" />
    <link rel="stylesheet" href="{{url('css/owl-carousel/owl.theme.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{url('css/main.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/stylesheets/app.css')}}">
    <script>
        var baseUrl = '{{url('/')}}';
    </script>
</head>
<body class="">
<div id="wrap">
    <!--    header-->
    <div id="block_header">
        @include('layouts.header')
    </div>


    <div class="main">
      @yield('content')
    </div><!-- end main-->

</div>

<!--    footer-->
<div id='block_footer'>
    @include('layouts.footer')
</div>

<div id="back-to-top"><i class="fa fa-angle-up"></i></div>
<script src="{{url('js/jquery-1.12.4.min.js')}}"></script>
<script src="{{url('js/bootstrap.min.js')}}"></script>
<script src="{{url('css/owl-carousel/owl.carousel.min.js')}}"></script>
<script src="{{url('js/imgLiquid-min.js')}}"></script>
<script src="{{url('http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js')}}"></script>
<script src="{{url('http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/additional-methods.min.js')}}"></script>
<script src="{{url('js/app.js')}}"></script>
</body>
</html>