<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\User;
use App\Models\Queries;
use App\Models\Follow;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    /**
     * Get all of the relevant profile information and pass it to the Profile view.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($username)
    {
        $currentUserId = 0;
        if(!Auth::guest()) {
            $currentUserId = Auth::user()->id;
        }
        $user = User::where('username', $username)->firstOrFail();
        $isFollowing = Follow::where(['user_id' => $currentUserId, 'following_id' => $user->id])->exists();

        return view('profile.index', ['id' => $user->id,
                                            'name' => $user->name,
                                            'username' => $user->username,
                                            'joined' => $user->created_at,
                                            'isFollowing' => $isFollowing,
                                            'numFollowing' => Queries::GetUsersSpecifiedUserIsFollowingCount($user),
                                            'numFollowers' => Queries::GetFollowersForUserCount($user),
                                            'numPosts' => Queries::GetUsersPostsCount($user),
                                            'numFavorites' => Queries::GetFavoritesForUserCount($user),
                                            'posts' => Queries::GetUsersPosts($user)]);
    }

    /**
     * Get all of users that a specified user has follow their profile and pass them to the view
     * @param $username The username of the profile to retrieve the followers for
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function followers($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $currentUser = Auth::user();
        $followers = Queries::GetFollowersForUser($user, $currentUser);
        return view('profile.followers', ['users' => $followers]);
    }

    /**
     * Get all of the users that a specified user is following and pass them to the view
     * @param $username The username of the profile to retrieve the list of users for
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function following($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $currentUser = Auth::user();
        $following = Queries::GetUsersSpecifiedUserIsFollowing($user, $currentUser);
        return view('profile.following', ['users' => $following]);
    }

    /**
     * Get all of the posts that a user has favorited and pass them to the view
     * @param $username The username of the profile to retrieve favorite posts for
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function favorites($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $favorites = Queries::GetFavoritesForUser($user);
        return view('profile.favorites', ['favorites' => $favorites]);
    }

    /**
     * Follow a specified user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function followUser(Request $request)
    {
        $userId = Auth::user()->id;
        $followingExists = User::findOrFail($request->following_id);

        Follow::create([
            'user_id' => $userId,
            'following_id' => $request->following_id
        ]);

        return redirect()->back();
    }

    /**
     * Unfollow a specified user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unfollowUser(Request $request)
    {
        $userId = Auth::user()->id;
        $followingExists = User::findOrFail($request->following_id);

        Queries::Unfollow($userId, $request->following_id);

        return redirect()->back();
    }
}