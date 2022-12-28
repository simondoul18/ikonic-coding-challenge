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

    // Suggestions
    public function getSuggestionsQuery()
    {
        return $suggestedQuery = User::where('id','!=',Auth::user()->id)
            ->whereNotIn('id', function($query) {
                $query->select('from_user_id')->from('requests')->where('to_user_id', '=', Auth::user()->id);
            })
            ->whereNotIn('id', function($query) {
                $query->select('to_user_id')->from('requests')->where('from_user_id', '=', Auth::user()->id);
            });
    }
    public function getSuggestions()
    {
        $query = $this->getSuggestionsQuery();
        $suggestedUsers = $query->paginate(10);
        return \Response::json($suggestedUsers);
    }

    // Requests
    public function sendRequest(Request $request)
    {
        $suggestionId = $request->get('suggestionId');
        $data = RequestModel::create([
            'from_user_id' => Auth::user()->id,
            'to_user_id' => $suggestionId
        ]);
                                
        return \Response::json($data);
    }

    public function getRequestsQuery($mode){
        return $requestedQuery = RequestModel::when($mode == 'send', function ($query) {
            $query->where('from_user_id', '=', Auth::user()->id)
                    ->join('users', 'users.id' ,'=', 'requests.to_user_id');
        }, function ($query) {
            $query->where('to_user_id', '=', Auth::user()->id)
                    ->join('users', 'users.id' ,'=', 'requests.from_user_id');
        })
        ->where('requests.status','!=', 'accepted')
        ->select('users.*','requests.id as request_id' );
    }

    public function getRequests($mode)
    {
        $query = $this->getRequestsQuery($mode);
        $requestedData = $query->paginate(10);

        return \Response::json($requestedData);
    }

    public function deleteRequest(Request $request)
    {
        $requestId = $request->get('requestId');
        $data = RequestModel::where('id',$requestId)->delete();
                                
        return \Response::json($data);
    }

    public function acceptRequest(Request $request)
    {
        $requestId = $request->get('requestId');
        $data = RequestModel::where('id',$requestId)->update(['status' => 'accepted']);
                                
        return \Response::json($data);
    }

    // Connections
    public function getConnectionsQuery($user_id="")
    {
        if (empty($user_id)) {
            $user_id = Auth::user()->id;
        }

        return $connectionsQuery = User::where('id','!=',$user_id)
            ->whereIn('id', function($query) use ($user_id){
                $query->select('from_user_id')->from('requests')->where('to_user_id', '=', $user_id)->where('status','=', 'accepted');
            })
            ->orWhereIn('id', function($query) use ($user_id){
                $query->select('to_user_id')->from('requests')->where('from_user_id', '=', $user_id)->where('status','=', 'accepted');
            });
    }
    public function getConnections()
    {
        $query = $this->getConnectionsQuery();
        $connectionsData = $query->paginate(10);

        $allConnections = $query->get();
        $myFriends = $allConnections->pluck('id')->toArray();

        foreach ($connectionsData->items() as $value) {
            $q = $this->getConnectionsQuery($value->id);
            $friendConnections = $q->get();
            $myFriendFriends = $friendConnections->pluck('id')->toArray();

            $commonFrndsArr = array_intersect($myFriends, $myFriendFriends);

            $commonFrnds = User::whereIn('id', $commonFrndsArr)->get();

            $value->commonFrnds = $commonFrnds;
            $value->commonFriendsCount = count($commonFrndsArr);
        }

        return \Response::json($connectionsData);
    }

    public function removeConnection(Request $request){
        $userId = $request->get('userId');
        $data = RequestModel::where(function($query) use ($userId){
            $query->where('from_user_id', Auth::user()->id)
                  ->where('to_user_id', $userId);
        })
        ->orWhere(function($query) use ($userId){
            $query->where('from_user_id', $userId)
                  ->where('to_user_id', Auth::user()->id);
        })
        ->where('status','=', 'accepted')->delete();
                                
        return \Response::json($data);
    }

    // Counts
    public function getCounts()
    {
        $suggestionsQuery = $this->getSuggestionsQuery();
        $suggestedUsersCount = $suggestionsQuery->count();

        $sRequestsQuery = $this->getRequestsQuery('send');
        $requestedDataSentCount = $sRequestsQuery->count();

        $rRequestsQuery = $this->getRequestsQuery('received');
        $requestedDataReceivedCount = $rRequestsQuery->count();

        $connectionsQuery = $this->getConnectionsQuery();
        $acceptedDataCount = $connectionsQuery->count();

        $counts = [
            'collection' => $acceptedDataCount,
            'request' => $requestedDataSentCount,
            'received' => $requestedDataReceivedCount,
            'suggestion' => $suggestedUsersCount
        ];

        return \Response::json($counts);
    }
}
