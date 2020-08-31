<?php

namespace App;

use App\Models\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class TeamMembers extends Model
{
    use UsesUuid ;
    protected $table = 'team_members';
    protected $fillable = ['name','email','team_id'];
}
