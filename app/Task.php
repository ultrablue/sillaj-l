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
}
