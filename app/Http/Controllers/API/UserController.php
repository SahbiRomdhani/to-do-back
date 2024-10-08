<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //we need paginate for infinity scroll
        $users = User::orderBy('first_name','ASC')
        ->select('first_name','last_name','id')
        ->get();
         return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function search(Request $request)
    {
        //validate that  $request->query('search') is not empty

         //paginate
         $limit = $request->query('limit', 10); // number of records per page
         $page = $request->query('page', 1);
         $offset = ($page - 1) * $limit; // calculate offset

        $user = User::where('first_name', 'like', "%{$request->query('search')}%")
        ->orWhere('last_name', 'like', "%{$request->query('search')}%")
        ->orderBy('id', 'DESC')
        ->offset($offset)
        ->limit($limit)
        ->get();

        return response()->json($user);
    }

}
