<?php

namespace App;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;
use Collective\Html\Eloquent\FormAccessible;
use DateInterval;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
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





    public static function rollUpTemp(CarbonImmutable $start, CarbonImmutable $end, User $user)
    {
        $eventsCollection = $user->events()
            ->whereBetween('event_date', [$start->toDateString(), $end->toDateString()])
            // ->where('duration', '>', 0)
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
        $query = self::join('projects', 'events.project_id', '=', 'projects.id')
            ->join('tasks', 'events.task_id', '=', 'tasks.id')
            ->where('events.user_id', '=', auth()->id())
            ->where('events.duration', '>', 0)
            // ->where('tasks.use_in_reports', '=', 1)
            ->whereBetween('events.event_date', [$start, $end])
            ->select('projects.name as project', 'tasks.name as task', DB::raw('SUM(duration) AS duration'))
            ->groupBy('project', DB::raw('task with rollup'));

        return $query->get();

        // ddd($query->get());
    }

    // This is the first draft of a query that gets by a "Task Group." A Task Group is an array of Tasks that are grouped for some reason. For example, Leaves.
    // TODO Should this be in User?
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
            ->addSelect('events.time_start')
            ->addSelect('events.time_end')
            ->groupBy('events.task_id', 'tasks.name', 'events.event_date', 'events.time_start', 'events.time_end')
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


    // TODO It looks like the join to get the project name happens fairly often. 
    // TODO Could the join to Projects be relation? Should it?
    // TODO I wonder why I have the join first in the other methods? It seems to make more sense to have the query builder methods sort of like SQL?
    /**
     * Returns a simple Collection that tallies the total Duration for all Projects related to the Events between two dates.
     * 
     * This is used for Reports, where displaying the total effort expended on a Project worked on during the time between two dates is very useful.
     *
     * @param CarbonImmutable $start The start date of the date range to report upon.
     * @param CarbonImmutable $end The end date of the date range to report upon.
     * @return Collection
     */
    public static function totalProjectTimeBetweenTwoDates(CarbonImmutable $start, CarbonImmutable $end): Collection
    {
        // TODO Should this return a query or a results set?
        // WYA: You need to implelement grouping by task, project. 

        $query = DB::table('events as e')
            ->leftJoin('projects', 'e.project_id', '=', 'projects.id')
            ->where('e.user_id', '=', auth()->id())
            ->whereBetween('e.event_date', [$start, $end])
            ->select('project_id', 'projects.name')
            ->addSelect(DB::raw('(SELECT SUM(duration) FROM events WHERE project_id = e.project_id AND user_id = ' . auth()->id() . ') AS total_duration'))
            ->groupBy('project_id')
            ->orderBy('projects.name');

        // dump($query->toSql(), auth()->id(), $start, $end);

        return $query->get();
    }




    // TODO URG!! DRY, please. This whole Model needs to be cleaned up.
    /**
     * Returns a simple Collection that tallies the total Duration for all Projects related to the Events between two dates.
     * 
     * This is used for Reports, where displaying the total effort expended on a Project worked on during the time between two dates is very useful.
     *
     * @param CarbonImmutable $start The start date of the date range to report upon.
     * @param CarbonImmutable $end The end date of the date range to report upon.
     * @return Collection
     */
    public static function totalTaskTimeBetweenTwoDates(CarbonImmutable $start, CarbonImmutable $end): Collection
    {
        // TODO Should this return a query or a results set?
        // WYA: You need to implelement grouping by task, project. 

        $query = DB::table('events as e')
            ->leftJoin('tasks', 'e.task_id', '=', 'tasks.id')
            ->where('e.user_id', '=', auth()->id())
            ->whereBetween('e.event_date', [$start, $end])
            ->select('task_id', 'tasks.name')
            ->addSelect(DB::raw('(SELECT SUM(duration) FROM events WHERE task_id = e.task_id AND user_id = ' . auth()->id() . ') AS total_duration'))
            ->groupBy('task_id')
            ->orderBy('tasks.name');

        // dump($query->toSql(), auth()->id(), $start, $end);

        return $query->get();
    }


    // WYA - Testing this one. Unfortunatly, tinker isn't all that helpful, so you're moving on to creating a route, etc.
    /**
     * Returns a Collection ready for presentation in Reports. 
     * 
     * @param mixed $ordering - Either a string or an array of strings representing the sort column(s).
     * @param CarbonImmutable $start
     * @param CarbonImmutable $end
     * @return QueryBuilder
     */
    public static function reportQueryTheLast(array $ordering, CarbonImmutable $start, CarbonImmutable $end): Builder
    {
        $query = self::join('projects', 'events.project_id', '=', 'projects.id')
            ->join('tasks', 'events.task_id', '=', 'tasks.id')
            ->where('events.user_id', '=', auth()->id())
            ->where('events.duration', '>', 0)
            ->whereBetween('events.event_date', [$start, $end])
            ->select('tasks.name as task', 'projects.name as project', 'events.duration', 'iso_8601_duration');

        foreach ($ordering as $item) {
            $query->orderBy($item);
        }

        // $r = $query->get();
        // dd($r);


        return $query;
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


    public function getTimeStartTimestampAttribute($value)
    {
        $timeStamp = $this->attributes['event_date'];
        if ($this->attributes['time_start']) {
            $timeStamp .= ' ' . $this->attributes['time_start'];
        }
        return new CarbonImmutable($timeStamp);
    }

    public function getTimeEndTimestampAttribute($value)
    {
        $timeStamp = $this->attributes['event_date'];
        if ($this->attributes['time_end']) {
            $timeStamp .= ' ' . $this->attributes['time_end'];
        }
        return new CarbonImmutable($timeStamp);
    }
}
