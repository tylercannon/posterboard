<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PosterBoard') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/site.css') }}" rel="stylesheet">
    @yield('additionalStyles')
</head>
<body class="site-bg-color">
    <div id="app">
        <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="/board">PosterBoard</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <!-- Add nav items here -->
                </ul>
                <ul class="navbar-nav">
                    <div class="searchDiv">
                        <input id="searchBox" type="text" class="searchBox" placeholder=" &#x1F50D; Search for users" onkeyup="searchBoxKeyup()">
                        <div id="searchResults" class="searchResults"></div>
                    </div>
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                    @else
                        <li class="nav-item dropdown">
                            <a href="#" id="userDropdownMenu" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="userDropdownMenu">
                                <a class="dropdown-item" href="/{{ Auth::user()->username }}">Profile</a>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        </li>
                        <li class="nav-item">
                            <button class="btn btn-sm btn-success" style="transform:translate(0, 15%)" data-toggle="modal" data-target="#postModal">New Post</button>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
        @yield('content')
    </div>

    @if(Auth::check())
        <div class="modal fade" id="postModal" tabindex="-1" role="dialog" aria-labelledby="postModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newPost">New Post</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="post-form" action="{{ route('createPost') }}" method="POST">
                            {{ csrf_field() }}
                            <textarea name="postContent" id="postContent" class="postContent" cols="30" rows="5"
                                      maxlength="140" placeholder="What's happening?" onkeyup="updateNumCharacters()" autofocus required></textarea>
                        </form>
                        <span id="maxChars" class="maxCharacters"><span id="numCharacters">0</span> / 140</span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button id="postSubmitBtn" type="button" class="btn btn-success" onclick="event.preventDefault(); document.getElementById('post-form').submit();" disabled>Post</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Scripts -->
    <script src="{{ asset('js/external/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('js/external/popper.min.js') }}"></script>
    <script src="{{ asset('js/external/popper-utils.min.js') }}"></script>
    <script src="{{ asset('js/external/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/external/vue.min.js') }}"></script>
    <script src="{{ asset('js/site.js') }}"></script>
    @yield('js')
</body>
</html>
