<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'subject', 'dueDate', 'state', 'parent_id', 'project_id', 'dependent'
    ];

    public function document()
    {
        return $this->hasMany(Document::class, "task_id");
    }

    public function project()
    {
        return $this->belongsTo(Projects::class);
    }

    public function user()
    {
        return $this->belongsToMany(User::class, 'user_tasks');
    }

    public function subTask()
    {
        return $this->hasMany(Tasks::class, 'parent_id');
//            ->with('subTask');
    }

    public function myparents()
    {
        return $this->belongsTo(Tasks::class, 'parent_id')->with('myparents');
    }

    public function category()
    {
        return $this->belongsTo(Categories::class, 'state');
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($task){
            Tasks::where("parents","like", "%|$task->id|%")->delete();
        });
    }
}
