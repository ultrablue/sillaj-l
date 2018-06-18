<?php

namespace App;

use App\Task;
use Illuminate\Database\Eloquent\Model;
use Auth;

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
     * Add a Task to a Project.
     * TODO Do we check here if the User owns the Project? Or in the Controller?
     *      It seems like we should be able to assume that the Project has already
     *      been chosen because it belongs to the User??
     */
    public function addTask($task)
    {
        $this->tasks()->create($task);
    }

    /**
     * Returns all shared Projects.
     */
    public function shared()
    {
        return $this->where('share', true)->get();
    }

    /**
     * Returns shared Projects that don't belong to $user.
     * Keep in mind that this query doesn't rely on a many to many.
     */
    public function scopeOtherShared()
    {
        return $this->where('share', '=',  true)
            ->where('user_id', '<>', Auth::id());
    }
    
    /**
     * Returns the User's Projects and Shared Projects that belong to any User.
     * Keep in mind that this query doesn't rely on a many to many.
     */
    public function scopeAllAvailable()
    {
        return $this->where('user_id','=',Auth::id())
            ->orWhere(function($query){
                $query->where('share', '=', true);
                $query->where('user_id', '<>', Auth::id());
            });
    }


    /**
     * Returns shared Projects that don't belong to $user.
     * Keep in mind that this query doesn't rely on a many to many.
     */
    public function scopeUsersProjects()
    {
        return $this->where('user_id', '=', 1);
    }
}
