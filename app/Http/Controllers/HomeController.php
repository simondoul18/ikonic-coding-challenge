<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Request as RequestModel;
use Auth;

use Illuminate\Http\Request;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    
    public function getSuggestions()
    {
        $requestedIds = RequestModel::where('from_user_id', '=', Auth::user()->id)->get()->pluck(['to_user_id'])->toArray();
        $receivedIds = RequestModel::where('to_user_id', '=', Auth::user()->id)->get()->pluck(['from_user_id'])->toArray();
        
        $suggestedUsers = User::where('id','!=',Auth::user()->id)
            ->whereNotIn('id', array_merge($requestedIds,$receivedIds))
            ->paginate(10);
                                
        return \Response::json($suggestedUsers);
    }

    public function getCounts()
    {
        $requestedIds = RequestModel::where('from_user_id', '=', Auth::user()->id)->where('status','=', 'accepted')->get()->pluck(['id'])->toArray();
        $receivedIds = RequestModel::where('to_user_id', '=', Auth::user()->id)->where('status','=', 'accepted')->get()->pluck(['id'])->toArray();

        $acceptedDataCount = RequestModel::whereIn('requests.id', array_merge($requestedIds,$receivedIds))
            ->join('users', 'users.id' ,'=', 'requests.from_user_id')
            ->where('users.id','!=',Auth::user()->id)->count();

        $requestedDataSentCount = RequestModel::where('from_user_id', '=', Auth::user()->id)
            ->join('users', 'users.id' ,'=', 'requests.to_user_id')
            ->where('requests.status','!=', 'accepted')->count();

        $requestedDataReceivedCount = RequestModel::where('to_user_id', '=', Auth::user()->id)
            ->join('users', 'users.id' ,'=', 'requests.from_user_id')
            ->where('requests.status','!=', 'accepted')->count();

        $requestedIds = RequestModel::where('from_user_id', '=', Auth::user()->id)->get()->pluck(['to_user_id'])->toArray();
        $receivedIds = RequestModel::where('to_user_id', '=', Auth::user()->id)->get()->pluck(['from_user_id'])->toArray();
        
        $suggestedUsersCount = User::where('id','!=',Auth::user()->id)
            ->whereNotIn('id', array_merge($requestedIds,$receivedIds))
            ->count();

        $counts = [
            'collection' => $acceptedDataCount,
            'request' => $requestedDataSentCount,
            'received' => $requestedDataReceivedCount,
            'suggestion' => $suggestedUsersCount
        ];

        return \Response::json($counts);
    }
}
