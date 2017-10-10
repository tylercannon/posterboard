<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StorePost;
use Auth;
use DB;
use App\User;
use App\Models\Queries;
use App\Models\Post;

class SearchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get all of the users that match the search string
     * @param Request $request
     * @return All the users that match the search string
     */
    public function search(Request $request)
    {
        $searchVal = '%' . $request->searchStr . '%';
        return DB::table('users')
            ->where('users.name', 'like', $searchVal)
            ->orWhere('users.username', 'like', $searchVal)
            ->select('users.id', 'users.name', 'users.username')
            ->get();
    }
}