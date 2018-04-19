<?php

namespace App;

use App\Project;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function projects()
    {
        return $this->belongsToMany(Project::class)->withTimestamps();
    }

    public function owner() {
        // We have to indicate that the column is actually called 'user_id'.
        return $this->belongsTo(User::class, 'user_id');
    }
}
