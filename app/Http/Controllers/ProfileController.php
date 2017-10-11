<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\User;
use App\Models\Queries;
use App\Models\Follow;
use App\Http\Requests\ProfileUpdate;

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
                                            'description' => $user->description,
                                            'joined' => $user->created_at,
                                            'headerPhoto' => $user->headerPhoto,
                                            'profilePhoto' => $user->profilePhoto,
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
     * Take the user to the edit page of their profile.
     *
     * @param $username The username of the profile to go to the edit page for
     */
    public function edit($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        if (Auth::check() && Auth::user()->id == $user->id) {
            return view('profile.edit', ['id' => $user->id,
                                               'username' => $user->username,
                                               'name' => $user->name,
                                               'headerPhoto' => $user->headerPhoto,
                                               'profilePhoto' => $user->profilePhoto,
                                               'description' => $user->description]);
        }
        //if the logged in user is not the profile owner, redirect them to the profile instead of the edit page.
        return redirect()->action('ProfileController@index', ['username' => $username]);
    }

    /**
     * Update the user's profile with new values.
     * NOTE: validation is done in the ProfileUpdate class
     * @param ProfileUpdate $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileUpdate $request)
    {
        if (Auth::check() && Auth::user()->username == $request->username) {
            $user = User::where('username', $request->username)->firstOrFail();
            $user->name = $request->name;
            $user->headerPhoto = $request->headerPhoto;
            $user->profilePhoto = $request->profilePhoto;
            $user->description = $request->description;
            $user->save();
        }

        return redirect()->action('ProfileController@index', ['username' => $request->username]);
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