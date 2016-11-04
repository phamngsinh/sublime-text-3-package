<header>
    <div class="block-logo text-center">
        <img src="{{url('images/logo.png')}}">
    </div>
    <div class="block-account dropdown">
        @if (\Auth::check())
        <a href="#" class="text-black dropdown-toggle " data-toggle="dropdown">
            <span style="vertical-align: -webkit-baseline-middle;" class="text-black">{{\Auth::user()->name}}</span>&nbsp;
            <img src="{{url('images/user-ico.png')}}">
        </a>
        @else
        <a href="#" class="text-black dropdown-toggle " data-toggle="dropdown">

            <img src="{{url('images/user-ico.png')}}">
        </a>
        @endif
        @if (\Auth::check())
            <ul class="dropdown-menu-list scroller dropdown-menu">
                <li><a href="{{route('users.edit', Auth::user()->id)}}" class="text-black">Your account</a></li>
                <li><a href="{{url('users/dashboard')}}" class="text-black">Dashboard</a></li>
                <li><a href="{{route('subscriptions.upgrade',Auth::user()->id)}}" class="text-black">Upgrade</a></li>
                <li><a href="{{url('/logout')}}" class="text-black">Logout</a></li>
            </ul>

        @else
            <ul class="dropdown-menu-list scroller dropdown-menu">
                <li><a href="{{url('/login')}}" class="text-black">Login</a></li>
                <li><a href="{{url('/register')}}" class="text-black">Register</a></li>
            </ul>
        @endif

    </div>
</header> <!-- end header -->
