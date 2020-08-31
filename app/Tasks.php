<?php

namespace App;

use App\Models\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    use UsesUuid ;
    protected $table = 'tasks';
    protected $fillable = ['title','description','assignee_id','team_id','status'];
}
