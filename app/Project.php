<?php

namespace App;

use App\Task;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{


    public function tasks() 
    {
        return $this->belongsToMany(Task::class)->withTimestamps();
    }


    /**
     *
     * Returns the string representation of the URI for this Model.
     * 
     * @return String the path(?).
     *
     * TODO There's an eloqent helper that might be able to do this.
     */
    public function path() 
    {
        // TODO If this is /projects/... it breaks. You should fix that.
        return 'projects/' . $this->id; 
    }

    public function owner() 
    {
        return $this->belongsTo(User::class, 'user_id'); 
    }

    /**
     * TODO Add phpDoc, please.
     */
    public function addTask($task) 
    {
        $this->tasks()->create($task);
    }

}
