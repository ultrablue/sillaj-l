<?php

namespace App;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;
use Collective\Html\Eloquent\FormAccessible;
use DateInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use FormAccessible;
    use SoftDeletes;

    protected $fillable = ['task_id', 'project_id', 'time_start', 'time_end', 'duration',
        'iso_8601_duration', 'event_date', 'note', ];

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
     * @param $day a Carbon date. The rollup will be made for that day.
     */
    public function dailyRollUpByProject(Carbon $day, string $user)
    {
        // TODO Don't pass in the user as a string, silly. Use Laravel's User stuff.
        // TODO Oops, it's not silly. This Action may or may not be called by an HTTP Controller.
        // Retrieve the currently authenticated user's ID...
        //$id = Auth::id();
        // $eventsCollection = $this->whereDate('event_date', '=', $day)->with(['task', 'project'])->where(['user_id' => $user])->get();
        $eventsCollection = \Auth::user()->events()->whereBetween('event_date', [$day->toDateString(), $day->toDateString()])->with(['task', 'project'])->get();
        // ddd($eventsCollection);
        // dd($eventsCollection);
        // $eventsCollection = $eventsCollection->sortBy(['project.name', 'task.name'])->groupBy(['project.name', 'task.name']);
        // $totalTime = $eventsCollection->pluck('*.duration');
        // dump($eventsCollection->keys());
        // dd($totalTime);
        // $eventsCollection = $eventsCollection->merge(['headers' => $groupDisplayArray]);
        // dd($eventsCollection);

        return $eventsCollection;
    }

    public function dailyRollUpByTask()
    {
    }

    /**
     * This is actually *last* month's roll up.
     */
    public function monthlyRollup(CarbonImmutable $date)
    {
        // Carbon is excellent.
        $startOfLastMonth = $date->subMonthsNoOverflow()->startOfMonth();
        $endOfLastMonth = $date->subMonthsNoOverflow()->endOfMonth();

        // We're only interested in the currently logged in User's Events.
        // TODO Oops, that wont' work. We'll need to pass in a User because this Controller could be called in a non-request context. :/
        $eventsCollection = \Auth::user()
            ->events()
            ->whereBetween('event_date', [$startOfLastMonth->toDateString(), $endOfLastMonth->toDateString()])
            ->with(['task', 'project'])
            ->get();

        return $eventsCollection;
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
