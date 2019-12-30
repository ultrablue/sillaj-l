<?php

namespace App;

use App\Project;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Task extends Model
{

    // TODO What???? Why do I want to set everything to fillable for testing?
    // Set everything to fillable for testing.
    // protected $guarded = [];

    protected $fillable = ['name', 'description', 'display', 'use_in_reports', 'share'];

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

    /**
     * Returns the User's Tasks and Shared Tasks that belong to any User.
     * Keep in mind that this query doesn't rely on a many to many.
     *
     * TODO - Should this go in User?
     */
    public function scopeAllAvailable()
    {
        return $this->where('user_id', '=', Auth::id())
            ->orWhere(function ($query) {
                $query->where('share', '=', true);
                $query->where('user_id', '<>', Auth::id());
            })->orderBy('name');
    }


}
