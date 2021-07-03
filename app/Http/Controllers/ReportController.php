<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $events = new Event();
        $eventsCollection = $events->whereBetween('event_date', ['2021-06-21', '2021-06-27'])->with(['task', 'project'])->get();
        $totalTime = $eventsCollection->sum('duration');
        // dd($totalTime / (60 * 60));
        $eventsCollection = $eventsCollection->sortBy(['project.name', 'task.name'])->groupBy(['project.name', 'task.name']);

        return view('reports.show', ['events' => $eventsCollection, 'total' => $totalTime]);
    }
}
