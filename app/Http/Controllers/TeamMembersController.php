<?php

namespace App\Http\Controllers;

use App\Tasks;
use App\TeamMembers;
use http\Env\Response;
use Illuminate\Http\Request;
use \Exception;
class TeamMembersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $member = TeamMembers::where([
            ['email','=',$request->email],
            ['team_id','=',$request->team_id]
        ])->get();
        if ( count($member) > 0 ){
            return response()->json([
                'Message' => "Email already associated with a team member",
            ],400);
        }
        $team_member = new TeamMembers;
        $team_member->name = $request->name;
        $team_member->team_id = $request->team_id;
        $team_member->email = $request->email;
        try {
            $team_member->save();
            return response()->json([
                'id' => $team_member->id,
                'name' => $team_member->name,
                'email' => $team_member->email
            ],201);
        }
        catch (Exception $e){
            if( strcmp((string)$e->getCode(),"2300") == 1 ){
                return response()->json([
                    'Message' => "Please provide valid Team ID",
                ],400);
            }
            return response()->json([
                'Message' => "DataBase Error Please Try Again",
            ],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TeamMembers  $teamMembers
     * @return \Illuminate\Http\Response
     */
    public function show(TeamMembers $teamMembers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TeamMembers  $teamMembers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TeamMembers $teamMembers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
//        dd($request->team_id);
        $member = TeamMembers::where([
            ['id','=',$request->id],
            ['team_id','=',$request->team_id]
        ])->get();
        if ( count($member) == 0 ){
            return response()->json([
                'Message' => "Team Member Not Present in specified Team",
            ],400);
        }
        $task = Tasks::where('assignee_id',$request->id)->get()->first();
        if( !is_null($task) ){
            return response()->json([
                'Message' => "Member cannot be deleted, please reassign all tasks from this member to someone else before trying again"
            ],400);
        }
        TeamMembers::destroy($request->id);
        return response('',204);
    }
}
