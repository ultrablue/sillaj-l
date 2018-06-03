<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

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
}
