<?php

namespace App;

use App\Project;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
   
   // Set everything to fillable for testing.
    protected $guarded = [];

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
        return 'tasks/' . $this->id;
    }


    /*
     * TODO Add phpDoc, please.
    */
    public function projects()
    {
        return $this->belongsToMany(Project::class)->withTimestamps();
    }

    public function owner()
    {
        // We have to indicate that the column is actually called 'user_id'.
        return $this->belongsTo(User::class, 'user_id');
    }
}
