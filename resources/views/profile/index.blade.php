@extends('layouts.app')

@section('content')

    <div class="header" name="header"></div>

    <div class="ribbon">
        <div style="text-align: center; transform: translate(0,10%)">
            <a id="numTweets" class="profileInfo" href="{{ sprintf('/%s', $username) }}">
                Posts<br><span class="innerLink">{{ $numPosts }}</span>
            </a>
            <a id="numFollowing" class="profileInfo" href="{{ sprintf('/%s/following', $username) }}">
                Following<br><span class="innerLink">{{ $numFollowing }}</span>
            </a>
            <a id="numFollowers" class="profileInfo" href="{{ sprintf('/%s/followers', $username) }}">
                Followers<br><span class="innerLink">{{ $numFollowers }}</span>
            </a>
            <a id="numFavorites" class="profileInfo" href="{{ sprintf('/%s/favorites', $username) }}">
                Favorites<br><span class="innerLink">{{ $numFavorites }}</span>
            </a>
            @if(Auth::check() && Auth::user()->id != $id)
                @if($isFollowing)
                    <button class="followButton" onclick="event.preventDefault(); document.getElementById('unfollow-form').submit();">Unfollow</button>

                    <form id="unfollow-form" action="{{ route('unfollow', ['following_id' => $id]) }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                @else
                    <button class="followButton" onclick="event.preventDefault(); document.getElementById('follow-form').submit();">Follow</button>

                    <form id="follow-form" action="{{ route('follow', ['following_id' => $id]) }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                @endif
            @elseif(Auth::check() && Auth::user()->id == $id)
                {{--<button class="followButton" onclick="event.preventDefault();">Edit</button>--}}

            @endif
        </div>
    </div>

    <div class="posterPhoto"></div>

    <div>
        <div class="row rowModifier">
            <div class="col-md-4 sideBar">
                <h3 id="name" class="row">{{ $name }}</h3>
                <span id="username" class="row">{{ sprintf("@%s", $username) }}</span>
                <span id="about" class="row">Description goes here</span>
                <span id="joinedDate" class="row">Joined {{ \Carbon\Carbon::parse($joined)->format('F Y') }}</span>
            </div>
            <div class="col-md-8 remainder">
                <div style="margin-top: 20px;">
                    @foreach ($posts as $post)
                        <div class="card" style="width: 50%; margin-top: 5px">
                            <div class="card-body">
                                <div style="display: inline-block">
                                    <span style="display:inline-block; font-weight:bold">{{ $post->name }}</span>
                                    <span> | </span><a style="display:inline-block" class="profileLink" href="/{{$post->username}}">{{ sprintf("@%s", $post->username) }}</a>
                                    <span style="display:inline-block; color: gray"> | {{ \Carbon\Carbon::parse($post->created_at)->format('d F Y') }}</span>
                                    <p>{{ $post->content }}</p>
                                    <div id="postButtons">
                                        @if (Auth::check() && $post->authUserDidRepost)
                                            <a class="postButton" href="#" onclick="event.preventDefault(); document.getElementById('unrepost-form-{{ $post->id }}').submit();">
                                                <i class="fa fa-retweet" style="color:green;" aria-hidden="true"></i> {{ $post->num_reposts == 0 ? '' : $post->num_reposts }}
                                            </a>
                                        @else
                                            <a class="postButton" href="#" onclick="event.preventDefault(); document.getElementById('repost-form-{{ $post->id }}').submit();">
                                                <i class="fa fa-retweet" aria-hidden="true"></i> {{ $post->num_reposts == 0 ? '' : $post->num_reposts }}
                                            </a>
                                        @endif
                                        @if (Auth::check() && $post->authUserDidFavorite)
                                            <a class="postButton" href="#" onclick="event.preventDefault(); document.getElementById('unfavorite-form-{{ $post->id }}').submit();">
                                                <i class="fa fa-heart" style="color:red;" aria-hidden="true"></i> {{ $post->num_favorites == 0 ? '' : $post->num_favorites }}
                                            </a>
                                        @else
                                            <a class="postButton" href="#" onclick="event.preventDefault(); document.getElementById('favorite-form-{{ $post->id }}').submit();">
                                                <i class="fa fa-heart" aria-hidden="true"></i> {{ $post->num_favorites == 0 ? '' : $post->num_favorites }}
                                            </a>
                                        @endif
                                        <form id="repost-form-{{ $post->id }}" action="{{ route('repost', ['post_id' => $post->id]) }}" method="post" style="display: none;">{{ csrf_field() }}</form>
                                        <form id="unrepost-form-{{ $post->id }}" action="{{ route('unrepost', ['post_id' => $post->id]) }}" method="post" style="display: none;">{{ csrf_field() }}</form>
                                        <form id="favorite-form-{{ $post->id }}" action="{{ route('favorite', ['post_id' => $post->id]) }}" method="post" style="display: none;">{{ csrf_field() }}</form>
                                        <form id="unfavorite-form-{{ $post->id }}" action="{{ route('unfavorite', ['post_id' => $post->id]) }}" method="post" style="display: none;">{{ csrf_field() }}</form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection