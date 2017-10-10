<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queries;

class BoardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Retrieve relevant variables for the logged in user and pass them to the view.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();

        return view('board.index', ['test' => 100,
                                          'numFollowing' => Queries::GetUsersSpecifiedUserIsFollowingCount($user),
                                          'numFollowers' => Queries::GetFollowersForUserCount($user),
                                          'numPosts' => Queries::GetUsersPostsCount($user),
                                          'posts' => Queries::GetUsersBoardPosts($user)]);
    }
}