<?php

namespace App;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;
use Collective\Html\Eloquent\FormAccessible;
use DateInterval;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Event extends Model
{
    use FormAccessible;
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'task_id', 'project_id', 'time_start', 'time_end', 'duration',
        'iso_8601_duration', 'event_date', 'note',
    ];

    // These will automatically be turned into Carbon Dates by the framework.
    protected $dates = ['event_date', 'created_at', 'updated_at'];

    protected $carbonTimeStart;

    /**
     * TODO I need some phpDoc, please.
     */
    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * TODO I need some phpDoc, please.
     */
    public function task()
    {
        return $this->belongsTo('App\Task');
    }

    /**
     * TODO I need some phpDoc, please.
     */
    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    /**
     * TODO I need some phpDoc, please.
     */
    public function start()
    {
        if ($this->time_start) {
            return Carbon::createFromTimeString($this->time_start);
        }
    }

    /**
     * TODO I need some phpDoc, please.
     */
    public function end()
    {
        return Carbon::createFromTimeString($this->time_end);
    }

    /**
     * @param carbonImmutable $start - The start date for the rollup
     * @param CarbonImmutable $end   - The end date for the rollup
     * @param User            $user  - A User
     *
     * @return Collection of Events
     */
    public static function rollUp(CarbonImmutable $start, CarbonImmutable $end, User $user)
    {
        $eventsCollection = $user->events()
            ->whereBetween('event_date', [$start->toDateString(), $end->toDateString()])
            ->with(['task', 'project'])
            ->get();

        return $eventsCollection;
    }


    /**
     * This returns a ROLL UPed results set, sorted and grouped by Project, Task.
     * Note that MySQL naturally sorts by the GROUP BY columns, in ASC order.
     * 
     */
    public static function rollupByProject(CarbonImmutable $start, CarbonImmutable $end)
    {
        return self::join('projects', 'events.project_id', '=', 'projects.id')
            ->join('tasks', 'events.task_id', '=', 'tasks.id')
            ->where('events.user_id', '=', auth()->id())
            ->where('events.duration', '>', 0)
            ->whereBetween('events.event_date', [$start, $end])
            ->select('projects.name as project', 'tasks.name as task', DB::raw('SUM(duration) AS duration'))
            ->groupBy('project', DB::raw('task with rollup'))
            ->get();
    }

    // This is the first draft of a query that gets by a "Task Group." A Task Group is an array of Tasks that are grouped for some reason. For example, Leaves.
    public static function filterByTaskGroup(CarbonImmutable $start, CarbonImmutable $end, array $group)
    {
        $result = self::join('tasks', 'events.task_id', '=', 'tasks.id')
            ->where('events.user_id', '=', auth()->id())
            ->where('events.duration', '>', 0)
            ->whereIn('events.task_id', $group)
            ->whereBetween('events.event_date', [$start, $end])
            ->select(DB::raw('SUM(events.duration) AS duration'))
            ->addSelect('tasks.name')
            ->addSelect('events.event_date')
            ->groupBy('events.event_date', 'events.task_id', 'tasks.name')
            ->orderBy('events.event_date')
            ->orderBy('events.task_id')
            ->get();

        return $result;
    }


    /**
     * This returns a ROLL UPed results set, sorted and grouped by Project, Task for a particular User.
     * Note that MySQL naturally sorts by the GROUP BY columns, in ASC order.
     * 
     * TODO This shold be merged with rollupByProject() in some way. I was just too lazy to do it right this time.
     * TODO You could try a scope here?
     */
    public static function rollupByProjectForUser(CarbonImmutable $start, CarbonImmutable $end, User $user)
    {
        // dd($start->format('Y-m-d'), $end, $user->id);
        // DB::enableQueryLog(); // Enable query log
        $results = self::join('projects', 'events.project_id', '=', 'projects.id')
            ->join('tasks', 'events.task_id', '=', 'tasks.id')
            ->where('events.user_id', '=', $user->id)
            ->where('events.duration', '>', 0)
            // TODO Why do I have to format the date here, but I don't above????
            ->whereBetween('events.event_date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->select('projects.name as project', 'tasks.name as task', DB::raw('SUM(duration) AS duration'))
            ->groupBy('project', DB::raw('task with rollup'))
            ->get();

        return $results;

        // dd(DB::getQueryLog()); // Show results of log
    }


    /**
     * This returns a ROLL UPed results set, sorted and grouped by Project, Task.
     * Note that MySQL naturally sorts by the GROUP BY columns, in ASC order.
     * 
     */
    public static function rollupByTask(CarbonImmutable $start, CarbonImmutable $end)
    {
        $records =  self::join('projects', 'events.project_id', '=', 'projects.id')
            ->join('tasks', 'events.task_id', '=', 'tasks.id')
            ->where('events.user_id', '=', auth()->id())
            ->where('events.duration', '>', 0)
            ->whereBetween('events.event_date', [$start, $end])
            ->select('tasks.name as task', 'projects.name as project', DB::raw('SUM(duration) AS duration'))
            ->groupBy('task', DB::raw('project with rollup'));


        return $records->get();
    }



    // Accessor and Mutators

    // When creating an Event with no event_date, set it to now.
    // TODO Can this be refactored? It seems wierd that I have to
    // set the attribute even if there was a value provided?
    // What I would like is to set the attribute only if it's NULL.
    // Which is what I'm doing here, but it seems wierd that if I
    // have to restore the value if it's set in the first place.
    public function setEventDateAttribute($value)
    {
        if (!$value) {
            $this->attributes['event_date'] = Carbon::now();
        } else {
            $this->attributes['event_date'] = $value;
        }
    }

    /**
     * Formats the time for display in a form.
     *
     * @param $value
     *
     * @return string
     */
    public function formTimeStartAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }

    /**
     * Formats the time for display in a form.
     *
     * @param $value
     *
     * @return string
     */
    public function formTimeEndAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }

    /**
     * Formats the duration for display in a form.
     *
     * @param $value
     *
     * @return string
     */
    public function formDurationAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }

    //    public function getDurationAttribute($value)
    //    {
    //        dd($value);
    //        // This is totally frustrating; neither carbonInterval nor
    //        // DateInterval do what's needed. So I'm rolling my own.
    //        // I'm also deeply dissatisfied with the fact that I'm bound
    //        // to the format 'HH:mm'. It shouldn't matter, but it's lame.
    //        //$di = new DateInterval('PT' . $value . 'S');
    //        //$carbonInterval = CarbonInterval::seconds($value);
    //        //$carbonInterval = CarbonInterval::instance($di);
    //        //return $carbonInterval;
    //
    //        if ($value) {
    //
    //            // extract hours
    //            $hours = floor($value / (60 * 60));
    //
    //            // extract minutes
    //            $divisor_for_minutes = $value % (60 * 60);
    //            $minutes = floor($divisor_for_minutes / 60);
    //
    //            return sprintf("%02d:%02d", $hours, $minutes);
    //        }
    //
    //        return $value;
    //    }

    //    public function setDurationAttribute($value)
    //    {
    ////        dump($this);
    ////        dd($value);
    //    }

    //TODO I need some docs, please.
    public function setIso8601DurationAttribute($value)
    {
        //        dd($this->attributes['duration']);
        if ($this->attributes['duration']) {
            $ci = CarbonInterval::seconds($this->attributes['duration'])->cascade()->spec();
            //            dd($ci);
            $this->attributes['iso_8601_duration'] = $ci;
        } else {
            $this->attributes['iso_8601_duration'] = null;
        }
    }

    //TODO I need some docs, please.
    public function getIso8601DurationAttribute($value)
    {
        $dur = 0;
        if ($this->attributes['iso_8601_duration']) {
            $dur = $this->attributes['iso_8601_duration'];
        }

        //        dd($dur);
        return CarbonInterval::fromString($dur);
    }
}
