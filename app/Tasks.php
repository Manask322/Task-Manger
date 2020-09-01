<?php

namespace App;

use App\Models\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    use UsesUuid ;
    protected $table = 'tasks';
    protected $fillable = ['title','description','assignee_id','team_id','status'];

    static function get_team_tasks($team_id){
        return Tasks::where([
            ['status','=','todo'],
            ['team_id', '=' , $team_id]
        ]);

    }

    static function  get_assignee_tasks($team_id,$member_id){
        return Tasks::where([
            ['status','=','todo'],
            ['team_id', '=' , $team_id],
            ['assignee_id', '=' ,$member_id]
        ]);

    }
}
