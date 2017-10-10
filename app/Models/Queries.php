<?php

namespace App\Models;

use DB;
use Illuminate\Support\Facades\Log;
use Auth;

class Queries
{
    // Getter functions
    public static function GetFollowersForUser($user, $authUser)
    {
        $followers = self::_GetFollowersForUser($user);
        $authUserFollows = self::_GetUsersSpecifiedUserIsFollowing($authUser);

        foreach ($followers as $follower)
        {
            $follower->authUserFollows = false;
            foreach ($authUserFollows as $authFollow)
            {
                if ($authFollow->id == $follower->id)
                {
                    $follower->authUserFollows = true;
                }
            }
        }

        return $followers;
    }

    public static function GetUsersSpecifiedUserIsFollowing($user, $authUser)
    {
        $following = self::_GetUsersSpecifiedUserIsFollowing($user);
        $authUserFollows = self::_GetUsersSpecifiedUserIsFollowing($authUser);

        foreach ($following as $follow)
        {
            $follow->authUserFollows = false;
            foreach ($authUserFollows as $authFollow)
            {
                if ($authFollow->id == $follow->id)
                {
                    $follow->authUserFollows = true;
                }
            }
        }

        return $following;
    }

    public static function GetFavoritesForUser($user)
    {
        $favorites = DB::table('favorites')
            ->join('posts', 'favorites.post_id', '=', 'posts.id')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->where('favorites.user_id', '=', $user->id)
            ->orderBy('favorites.created_at', 'desc')
            ->select('users.name', 'users.username', 'posts.*')
            ->get();

        $postIds = self::_GetUsersFavoritesPostIds($user);
        $authUsersReposts = self::GetAuthUsersRepostsByPostIds($postIds);
        $authUsersFavorites = self::GetAuthUsersFavoritesByPostIds($postIds);

        foreach($favorites as $favorite)
        {
            $favorite->authUserDidRepost = false;
            $favorite->authUserDidFavorite = false;
            foreach($authUsersReposts as $authUsersRepost)
            {
                if ($favorite->id == $authUsersRepost->post_id)
                {
                    $favorite->authUserDidRepost = true;
                }
            }

            foreach($authUsersFavorites as $authUsersFavorite)
            {
                if ($favorite->id == $authUsersFavorite->post_id)
                {
                    $favorite->authUserDidFavorite = true;
                }
            }
        }

        return $favorites;
    }

    public static function GetUsersPosts($user)
    {
        $posts = DB::table('posts')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->where('users.id', '=', $user->id)
            ->orderBy('posts.created_at', 'desc')
            ->select('posts.*', 'users.name', 'users.username')
            ->get();

        if (Auth::check())
        {
            $postIds = self::_GetUsersPostsIds($user);
            $authUsersReposts = self::GetAuthUsersRepostsByPostIds($postIds);
            $authUsersFavorites = self::GetAuthUsersFavoritesByPostIds($postIds);

            foreach($posts as $post)
            {
                $post->authUserDidRepost = false;
                $post->authUserDidFavorite = false;
                foreach($authUsersReposts as $authUsersRepost)
                {
                    if ($post->id == $authUsersRepost->post_id)
                    {
                        $post->authUserDidRepost = true;
                    }
                }

                foreach($authUsersFavorites as $authUsersFavorite)
                {
                    if ($post->id == $authUsersFavorite->post_id)
                    {
                        $post->authUserDidFavorite = true;
                    }
                }
            }
        }

        return $posts;
    }

    public static function GetUsersBoardPosts($user)
    {
        $posts = DB::table('posts as p')
            ->join('users as u', 'p.user_id', '=', 'u.id')
            ->whereIn('p.user_id', DB::table('follows')->where('user_id', '=', $user->id)->select('following_id'))
            ->orWhere('p.user_id', '=', $user->id)
            ->orderBy('p.created_at', 'desc')
            ->select('p.*', 'u.name', 'u.username')
            ->get();

        $postIds = self::_GetUsersBoardPostsIds($user);
        $authUsersReposts = self::GetAuthUsersRepostsByPostIds($postIds);
        $authUsersFavorites = self::GetAuthUsersFavoritesByPostIds($postIds);

        foreach($posts as $post)
        {
            $post->authUserDidRepost = false;
            $post->authUserDidFavorite = false;
            foreach($authUsersReposts as $authUsersRepost)
            {
                if ($post->id == $authUsersRepost->post_id)
                {
                    $post->authUserDidRepost = true;
                }
            }

            foreach($authUsersFavorites as $authUsersFavorite)
            {
                if ($post->id == $authUsersFavorite->post_id)
                {
                    $post->authUserDidFavorite = true;
                }
            }
        }

        return $posts;
    }

    public static function GetAuthUsersRepostsByPostIds($postIds)
    {
        return DB::table('reposts')
            ->whereIn('post_id', $postIds)
            ->where('user_id', '=', Auth::user()->id)
            ->select('post_id')
            ->get();
    }

    public static function GetAuthUsersFavoritesByPostIds($postIds)
    {
        return DB::table('favorites')
            ->whereIn('post_id', $postIds)
            ->where('user_id', '=', Auth::user()->id)
            ->select('post_id')
            ->get();
    }

    // Count functions
    public static function GetFollowersForUserCount($user)
    {
        return DB::table('follows')
            ->join('users', 'follows.following_id', '=', 'users.id')
            ->where('follows.following_id', '=', $user->id)
            ->select('users.id')
            ->count();
    }

    public static function GetUsersSpecifiedUserIsFollowingCount($user)
    {
        return DB::table('follows')
            ->join('users', 'follows.following_id', '=', 'users.id')
            ->where('follows.user_id', '=', $user->id)
            ->select('users.id')
            ->count();
    }

    public static function GetFavoritesForUserCount($user)
    {
        return DB::table('favorites')
            ->join('users', 'favorites.user_id', '=', 'users.id')
            ->where('users.id', '=', $user->id)
            ->select('id')
            ->count();
    }

    public static function GetUsersPostsCount($user)
    {
        return DB::table('posts')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->where('users.id', '=', $user->id)
            ->select('posts.*', 'users.name', 'users.username')
            ->count();
    }


    // Getter helper functions
    private static function _GetFollowersForUser($user)
    {
        return DB::table('follows')
            ->join('users', 'follows.user_id', '=', 'users.id')
            ->where('follows.following_id', '=', $user->id)
            ->select('users.id', 'users.name', 'users.username')
            ->get();
    }

    private static function _GetUsersSpecifiedUserIsFollowing($user)
    {
        return DB::table('follows')
            ->join('users', 'follows.following_id', '=', 'users.id')
            ->where('follows.user_id', '=', $user->id)
            ->select('users.id', 'users.name', 'users.username')
            ->get();
    }

    private static function _GetUsersPostsIds($user)
    {
        return DB::table('posts')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->where('users.id', '=', $user->id)
            ->orderBy('posts.created_at', 'desc')
            ->pluck('posts.id');
    }

    private static function _GetUsersBoardPostsIds($user)
    {
        return DB::table('posts as p')
            ->join('users as u', 'p.user_id', '=', 'u.id')
            ->whereIn('p.user_id', DB::table('follows')->where('user_id', '=', $user->id)->select('following_id'))
            ->orWhere('p.user_id', '=', $user->id)
            ->orderBy('p.created_at', 'desc')
            ->pluck('p.id');
    }

    private static function _GetUsersFavoritesPostIds($user)
    {
        return DB::table('favorites')
            ->join('posts', 'favorites.post_id', '=', 'posts.id')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->where('favorites.user_id', '=', $user->id)
            ->orderBy('favorites.created_at', 'desc')
            ->pluck('posts.id');
    }


    // Deletion functions
    public static function Unfollow($userId, $toUnfollowId)
    {
        DB::table('follows')->where(['user_id' => $userId, 'following_id' => $toUnfollowId])->delete();
    }

    public static function Unrepost($userId, $postId)
    {
        DB::table('reposts')->where(['user_id' => $userId, 'post_id' => $postId])->delete();
    }

    public static function Unfavorite($userId, $postId)
    {
        DB::table('favorites')->where(['user_id' => $userId, 'post_id' => $postId])->delete();
    }
}