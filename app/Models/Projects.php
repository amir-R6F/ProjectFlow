<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    protected $fillable = ['title', 'admin', 'members', 'state', 'dueDate'];
    use HasFactory;

    protected $appends = ['members_list'];

    public function getMembersListAttribute()
    {
        $users = [];

        if (strlen($this->members) > 1) {
            $lists = explode('|', substr($this->members, 1, strlen($this->members) - 2));
            foreach ($lists as $list) {
                $users [] = User::select('id', 'name')->firstWhere('id', $list);
            }

        } else {
            $users [] = User::select('id', 'name')->firstWhere('id', $this->members);
        }
        return $users;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'admin');
    }

    public function task()
    {
        return $this->hasMany(Tasks::class, 'project_id');
    }

    public function category()
    {
        return $this->hasMany(Categories::class, 'project_id');
    }
}
