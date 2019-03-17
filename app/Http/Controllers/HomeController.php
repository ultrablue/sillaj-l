<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;

use Carbon\Carbon;

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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $eventdate = null)
    {

        if (!$eventdate){
            $searchForDate  = Carbon::now();
        } else {
            $searchForDate = Carbon::createFromFormat('Y-m-d', $eventdate);
            if ($searchForDate->format('Y-m-d') != $eventdate) {
                abort(404);
            }
        }

        //dd($eventdate);
        // This is a comment.
        $events = Event::where(['user_id' => $request->user()->id])->whereDate('event_date' , Carbon::now()->toDateString())->get();
        return view('home', compact('events'));
    }
}
