<?php

namespace App;

use App\Models\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Teams extends Model
{
    use UsesUuid ;
    protected $table = 'teams';
    protected $fillable = ['name'];
}
