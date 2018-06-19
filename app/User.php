<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
    * The User's Events.
    *
    * @var ?? A collection?
    */
    public function events()
    {
        return $this->hasMany('App\Event');
    }



    /**
    * Get the User's Projects.
    *
    * @var ?? A collection?
    */
    public function projects()
    {
        return $this->hasMany('App\Project');
    }

    /**
    * Get the User's Tasks.
    *
    * @var ?? A collection?
    */
    public function tasks()
    {
        return $this->hasMany('App\Task');
    }
}
