@extends('layouts.app')

@section('content')
    <div class="row justify-content-center rowModifier">
        <div class="col-md-4 ml-md-8" style="margin-top: 20px;">
            @foreach ($favorites as $favorite)
                <div class="card" style="width: 100%; margin-top: 5px">
                    <div class="card-body">
                        <div style="display: inline-block">
                            <span style="display:inline-block; font-weight:bold">{{ $favorite->name }}</span>
                            <span> | </span><a style="display:inline-block" class="profileLink" href="/{{$favorite->username}}">{{ sprintf("@%s", $favorite->username) }}</a>
                            <span style="display:inline-block; color: gray"> | {{ \Carbon\Carbon::parse($favorite->created_at)->format('d F Y') }}</span>
                            <p>{{ $favorite->content }}</p>
                            <div id="postButtons">
                                @if (Auth::check() && $favorite->authUserDidRepost)
                                    <a class="postButton" href="#" onclick="event.preventDefault(); document.getElementById('unrepost-form-{{ $favorite->id }}').submit();">
                                        <i class="fa fa-retweet" style="color:green;" aria-hidden="true"></i> {{ $favorite->num_reposts == 0 ? '' : $favorite->num_reposts }}
                                    </a>
                                @else
                                    <a class="postButton" href="#" onclick="event.preventDefault(); document.getElementById('repost-form-{{ $favorite->id }}').submit();">
                                        <i class="fa fa-retweet" aria-hidden="true"></i> {{ $favorite->num_reposts == 0 ? '' : $favorite->num_reposts }}
                                    </a>
                                @endif
                                @if (Auth::check() && $favorite->authUserDidFavorite)
                                    <a class="postButton" href="#" onclick="event.preventDefault(); document.getElementById('unfavorite-form-{{ $favorite->id }}').submit();">
                                        <i class="fa fa-heart" style="color:red;" aria-hidden="true"></i> {{ $favorite->num_favorites == 0 ? '' : $favorite->num_favorites }}
                                    </a>
                                @else
                                    <a class="postButton" href="#" onclick="event.preventDefault(); document.getElementById('favorite-form-{{ $favorite->id }}').submit();">
                                        <i class="fa fa-heart" aria-hidden="true"></i> {{ $favorite->num_favorites == 0 ? '' : $favorite->num_favorites }}
                                    </a>
                                @endif
                                <form id="repost-form-{{ $favorite->id }}" action="{{ route('repost', ['post_id' => $favorite->id]) }}" method="post" style="display: none;">{{ csrf_field() }}</form>
                                <form id="unrepost-form-{{ $favorite->id }}" action="{{ route('unrepost', ['post_id' => $favorite->id]) }}" method="post" style="display: none;">{{ csrf_field() }}</form>
                                <form id="favorite-form-{{ $favorite->id }}" action="{{ route('favorite', ['post_id' => $favorite->id]) }}" method="post" style="display: none;">{{ csrf_field() }}</form>
                                <form id="unfavorite-form-{{ $favorite->id }}" action="{{ route('unfavorite', ['post_id' => $favorite->id]) }}" method="post" style="display: none;">{{ csrf_field() }}</form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection