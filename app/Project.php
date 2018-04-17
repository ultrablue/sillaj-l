<?php

namespace App;

use App\Task;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function tasks() {
        return $this->belongsToMany(Task::class)->withTimestamps();
    }


    /**
     *
     * Returns the string representation of the URI for this Model.
     * 
     * @return String the path(?).
     *
     */
    public function path() {
        // TODO If this is /projects/... it breaks. You should fix that.
        return 'projects/' . $this->id; 
    }



}
