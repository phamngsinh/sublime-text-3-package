<!DOCTYPE html>
<html>
<head>
    <title>Risk24.org</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Kiyoshi-lamnv_qsoft VietNam">
    <link rel="icon" type="image/png" href="http://creativelybeba.com/wp-content/uploads/2011/09/twitter-bird.jpg">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" type="text/css" href="{{url('assets/stylesheets/all.css')}}">
    <script>
        var baseUrl = '{{url('/')}}';
    </script>
    @yield('page_styles')
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
<script src="{{url('assets/scripts/app.js')}}"></script>
@yield('scripts')

</body>
</html>