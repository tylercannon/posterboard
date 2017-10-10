@extends('layouts.app')

@section('content')
    <div class="row justify-content-center rowModifier">
        <div style="margin-top: 20px;">
            @foreach ($users as $user)
                <div class="card" style="width: 400px; margin-top: 5px">
                    <div class="card-body">
                        <div style="display: inline-block">
                            <h3>{{ $user->name }}</h3>
                            <a class="profileLink" href="/{{$user->username}}">{{ sprintf("@%s", $user->username) }}</a>
                        </div>
                        <div style="display: inline-block; float: right">
                            @if ($user->authUserFollows)
                                <button class="followButton" onclick="event.preventDefault(); document.getElementById('unfollow-form').submit();">Following</button>

                                <form id="unfollow-form" action="{{ route('unfollow', ['following_id' => $user->id]) }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            @elseif (Auth::user()->id != $user->id)
                                <button class="followButton" onclick="event.preventDefault(); document.getElementById('follow-form').submit();">Follow</button>

                                <form id="follow-form" action="{{ route('follow', ['following_id' => $user->id]) }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            @endIf
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection