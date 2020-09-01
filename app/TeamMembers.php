<?php

namespace App;

use App\Models\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class TeamMembers extends Model
{
    use UsesUuid ;
    protected $table = 'team_members';
    protected $fillable = ['name','email','team_id'];

    static function  get_team_member($id,$team_id){
        return TeamMembers::where([
            ['id','=',$id],
            ['team_id','=',$team_id]
        ]);
    }
}
