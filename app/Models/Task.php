<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['name','email','contact_number','gender','profile_pic','state_id','city_id'];
    public function state()
    {
        return $this->belongsTo(State::class)->select('id', 'name');
    }

    public function city()
    {
        return $this->belongsTo(City::class)->select('id', 'name');
    }

    public function hobbies()
    {
        return $this->hasMany(Hobby::class)->select('task_id', 'name');
    }
}
