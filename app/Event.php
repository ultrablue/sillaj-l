<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use DateInterval;

class Event extends Model
{

    protected $fillable=['task_id', 'project_id', 'time_start', 'time_end', 'duration', 'event_date', 'note'];

    protected $dates = ['event_date', 'created_at', 'updated_at'];

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
    public function start() {
        return Carbon::createFromTimeString($this->time_start);
    }

    /**
     * TODO I need some phpDoc, please.
     */
    public function end() {
        return Carbon::createFromTimeString($this->time_end);
    }

    // When creating an Event with no event_date, set it to now.
    // TODO Can this be refactored? It seems wierd that I have to
    // set the attribute even if there was a value provided?
    // What I would like is to set the attribute only if it's NULL.
    // Which is what I'm doing here, but it seems wierd that if I
    // have to restore the value if it's set in the first place.
    public function setEventDateAttribute($value) {
        if (!$value) {
            $this->attributes['event_date'] = Carbon::now();
        } else {
            $this->attributes['event_date'] = $value;
        }
    }

    public function getDurationAttribute($value)
    {
        // This is totally frustrating; neither carbonInterval nor
        // DateInterval do what's needed. So I'm rolling my own.
        // I'm also deeply dissatisfied with the fact that I'm bound
        // to the format 'HH:mm'. It shouldn't matter, but it's lame.
        //$di = new DateInterval('PT' . $value . 'S');
        //$carbonInterval = CarbonInterval::seconds($value);
        //$carbonInterval = CarbonInterval::instance($di);
        //return $carbonInterval;

        // extract hours
        $hours = floor($value / (60 * 60));

        // extract minutes
        $divisor_for_minutes = $value % (60 * 60);
        $minutes = floor($divisor_for_minutes / 60);
        
        return sprintf("%02d:%02d", $hours, $minutes);

    }
}
