<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StashDateInSession
{
    /**
     * Handle an incoming request.
     * Sets the eventdate in the Session for use downstream. The idea is to ensure that eventdate is "sticky."
     * In other words, if the user selects a date to view or edit, then that date should persist between updates.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $request->session()->put('eventdate', $request->route('eventdate'));

        return $next($request);
    }
}
