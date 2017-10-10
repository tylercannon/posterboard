<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StorePost;
use App\Http\Requests\PostReaction;
use Auth;
use DB;
use App\User;
use App\Models\Queries;
use App\Models\Post;
use App\Models\Repost;
use App\Models\Favorite;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
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
     * Create a new post for the logged in user.
     * NOTE: validation is handled via the StorePost class
     * @param StorePost $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post(StorePost $request)
    {
        $userId = Auth::user()->id;

        Post::create([
           'user_id' => $userId,
           'content' => $request->postContent
        ]);

        return redirect()->back();
    }

    /**
     * Repost a specified post for the logged in user.
     * NOTE: validation is handled via the PostReaction class
     * @param PostReaction $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function repost(PostReaction $request)
    {
        $userId = Auth::user()->id;

        DB::beginTransaction();
        Repost::create([
            'user_id' => $userId,
            'post_id' => $request->post_id
        ]);

        $post = Post::find($request->post_id);
        $post->num_reposts++;
        $post->save();
        DB::commit();

        return redirect()->back();
    }

    /**
     * Favorite a specified post for the logged in user.
     * NOTE: validation is handled via the PostReaction class
     * @param PostReaction $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function favorite(PostReaction $request)
    {
        $userId = Auth::user()->id;

        DB::beginTransaction();
        Favorite::create([
            'user_id' => $userId,
            'post_id' => $request->post_id
        ]);

        $post = Post::find($request->post_id);
        $post->num_favorites++;
        $post->save();
        DB::commit();

        return redirect()->back();
    }

    /**
     * Delete a repost for a specified post for the logged in user.
     * NOTE: validation is handled via the PostReaction class
     * @param PostReaction $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unrepost(PostReaction $request)
    {
        $userId = Auth::user()->id;

        DB::beginTransaction();

        Queries::Unrepost($userId, $request->post_id);

        $post = Post::find($request->post_id);
        $post->num_reposts--;
        $post->save();
        DB::commit();

        return redirect()->back();
    }

    /**
     * Delete a favorite for a specified post for the logged in user.
     * NOTE: validation is handled via the PostReaction class
     * @param PostReaction $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unfavorite(PostReaction $request)
    {
        $userId = Auth::user()->id;

        DB::beginTransaction();

        Queries::Unfavorite($userId, $request->post_id);

        $post = Post::find($request->post_id);
        $post->num_favorites--;
        $post->save();
        DB::commit();

        return redirect()->back();
    }
}