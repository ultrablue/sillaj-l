<?php

namespace App;

use App\Task;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function tasks() {
        return $this->belongsToMany(Task::class)->withTimestamps();
    }
}
