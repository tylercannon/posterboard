@extends('layouts.app')

@section('content')
    <div class="row rowModifier">
        <div class="col-md-3">
            <div class="card" style="margin-top:10px">
                <div class="card-body">
                    <h3 class="card-title">{{ Auth::user()->name }}</h3>
                    <a class="profileLink" href="/{{ Auth::user()->username }}">{{ sprintf("@%s", Auth::user()->username) }}</a>
                    <hr>
                    <div style="text-align: center">
                        <a id="numTweets" class="profileInfo" href="{{ sprintf('/%s', Auth::user()->username) }}">
                            Posts<br><span class="innerLink">{{ $numPosts }}</span>
                        </a>
                        <a id="numFollowing" class="profileInfo" href="{{ sprintf('/%s/following', Auth::user()->username) }}">
                            Following<br><span class="innerLink">{{ $numFollowing }}</span>
                        </a>
                        <a id="numFollowers" class="profileInfo" href="{{ sprintf('/%s/followers', Auth::user()->username) }}">
                            Followers<br><span class="innerLink">{{ $numFollowers }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div id="whatsHappening" style="width: 100%; margin-top:10px; display: block; border-radius: 4px;">
                <form id="board-post-form" action="{{ route('createPost') }}" method="POST">
                    {{ csrf_field() }}
                    <textarea name="postContent" id="boardPostContent" class="postContent" cols="30" rows="1" maxlength="140"
                              placeholder="What's happening?" onkeyup="updateBoardNumCharacters()" required></textarea>
                </form>
                <button id="boardPostSubmit" class="btn btn-sm btn-success"
                        style="float:right; margin-right: 10px" onclick="event.preventDefault(); document.getElementById('board-post-form').submit();" hidden disabled>
                    Post
                </button>
                <span id="boardMaxChars" class="maxCharacters" style="margin-top: 5px;margin-right: 10px;" hidden><span id="boardNumCharacters">0</span> / 140</span>
            </div>
            <div style="margin-top: 5px;">
                @foreach ($posts as $post)
                    <div class="card" style="width: 100%; margin-top: 5px">
                        <div class="card-body">
                            <div style="display: inline-block">
                                <span style="display:inline-block; font-weight:bold">{{ $post->name }}</span>
                                <span> | </span><a style="display:inline-block" class="profileLink" href="/{{$post->username}}">{{ sprintf("@%s", $post->username) }}</a>
                                <span style="display:inline-block; color: gray"> | {{ \Carbon\Carbon::parse($post->created_at)->format('d F Y') }}</span>
                                <p>{{ $post->content }}</p>
                                <div id="postButtons">
                                    @if ($post->authUserDidRepost)
                                        <a class="postButton" href="#" onclick="event.preventDefault(); document.getElementById('unrepost-form-{{ $post->id }}').submit();">
                                            <i class="fa fa-retweet" style="color:green;" aria-hidden="true"></i> {{ $post->num_reposts == 0 ? '' : $post->num_reposts }}
                                        </a>
                                    @else
                                        <a class="postButton" href="#" onclick="event.preventDefault(); document.getElementById('repost-form-{{ $post->id }}').submit();">
                                            <i class="fa fa-retweet" aria-hidden="true"></i> {{ $post->num_reposts == 0 ? '' : $post->num_reposts }}
                                        </a>
                                    @endif
                                    @if ($post->authUserDidFavorite)
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
        <div class="col-md-3">
            <!-- Friend suggestions would go here -->
        </div>
    </div>
@endsection