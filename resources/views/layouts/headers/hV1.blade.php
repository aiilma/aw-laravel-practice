        <nav class="navbar navbar-expand-md navbar-dark navbar-artworch py-0">
            <div class="container">
                <a class="navbar-brand" href="{{ route('compositions') }}">
                    <img src="{{asset('storage/img/logo_white.svg')}}" alt="Artworch" width="48">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse align-items-end" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item"><a class="aw-nav-link" href="{{ route('compositions') }}"><span>Compositions</span></a></li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="aw-nav-link" href="{{ route('acc-login') }}">{{ __('Login') }}</a>
                            </li>
                            <li class="nav-item">
                                @if (Route::has('acc-register'))
                                    <a class="aw-nav-link" href="{{ route('acc-register') }}">{{ __('Register') }}</a>
                                @endif
                            </li>
                        @else
                            {{-- balance --}}
                            <li class="nav-item dropdown aw__ui__balance">
                                {!!auth()->user()->getBalanceHtml()!!}
                            </li>
                            {{-- dropdown menu --}}
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="ui aw-nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->username }} <span class="caret"></span>
                                </a>

                                <div id="awAccountDropdownDefault" class="dropdown-menu dropdown-menu-right text-center" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('acc-home') }}">
                                        {{ __('My Account') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('acc-logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('acc-logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>