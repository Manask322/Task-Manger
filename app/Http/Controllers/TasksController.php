<?php

namespace App\Http\Controllers;

use App\Tasks;
use App\TeamMembers;
use Illuminate\Http\Request;
use \Exception;
class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->page_id < 1){
            return response()->json([
                "Message" => "Enter valid page number."
            ],400);
        }
        $offset = ($request->page_id - 1)*3;
        $tasks = Tasks::where([
            ['status','=','todo'],
            ['team_id', '=' , $request->team_id]
        ])->select('id','title','description','assignee_id','status')->orderBy('title', 'desc')->offset($offset)->limit(3)->get();
        $tasks = $tasks->toJson(JSON_PRETTY_PRINT);
        return response($tasks,200);
    }

    public function member_index(Request $request)
    {
        if($request->page_id < 1){
            return response()->json([
                "Message" => "Enter valid page number."
            ],400);
        }
        $offset = ($request->page_id - 1)*3;
        $tasks = Tasks::where([
            ['status','=','todo'],
            ['team_id', '=' , $request->team_id],
            ['assignee_id', '=' , $request->member_id]
        ])->select('id','title','description','assignee_id','status')->orderBy('title', 'desc')->offset($offset)->limit(3)->get();
        $tasks = $tasks->toJson(JSON_PRETTY_PRINT);
        if( strlen($tasks) <= 2 ){
            return response()->json([
                "Message" => "No Record found for the given page number, please retry with another page number."
            ],200);
        }
        return response($tasks,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if( strlen($request->title) == 0 ){
            return response()->json([
                'Message' => "Title Should Not be Null.",
            ],400);
        }
        if( strcmp($request->status,"todo") != 0 ){
            if (strcmp($request->status,"done") != 0){
                return response()->json([
                    'Message' => "Status Should be todo or done.",
                ],400);
            }
        }
        $member = TeamMembers::where([
            ['id','=',$request->assignee_id],
            ['team_id','=',$request->team_id]
        ])->get();
        if ( count($member) == 0 && (strlen($request->assignee_id) != 0) ){
            return response()->json([
                'Message' => "assignee_id should belong to the same team as the task.",
            ],400);
        }
        $task = new Tasks;
        $task->title = $request->title;
        $task->description = $request->description;
        $task->assignee_id = $request->assignee_id;
        $task->team_id = $request->team_id;
        $task->status = $request->status;
        try {
            $task->save();
            return response()->json([
                "id" => $task->id,
                "title" => $task->title,
                "description" => $task->description,
                "assignee_id" => $task->assignee_id,
                "status" => $task->status
            ],201);
        }
        catch (Exception $e){
            return response()->json([
                'Message' => "Data Error Please Try Again",
                'Error' => $e->getMessage()
            ],500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tasks  $tasks
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $task = Tasks::where([
            ['id' , '=', $request->id],
            ['team_id', '=' , $request->team_id]
        ])->select('id','title','description','assignee_id','status')->get()->first();
        if( !$task ){
            return response()->json([
                "Message" => "Task Not Found."
            ],200);
        }
        return response()->json([
            "id" => $task->id,
            "title" => $task->title,
            "description" => $task->description,
            "assignee_id" => $task->assignee_id,
            "status" => $task->status
        ],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tasks  $tasks
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        if( strcmp($request->status,"todo") != 0 ){
            if (strcmp($request->status,"done") != 0){
                return response()->json([
                    'Message' => "Status Should be todo or done.",
                ],400);
            }
        }
        $task = Tasks::find($request->id);
        if (is_null($task)){
            return response()->json([
                'Message' => "Task Not found for the provided ID.",
            ],400);
        }
        if( $request->assignee_id ){
            $member = TeamMembers::where([
                ['id','=',$request->assignee_id],
                ['team_id','=',$request->team_id]
            ])->get()->first();
            if ( is_null($member) ){
                return response()->json([
                    'Message' => "assignee_id should belong to the same team as the task.",
                ],400);
            }
            $task->assignee_id = $request->assignee_id;

        }
        if( $request->title ){
            $task->title = $request->title;
        }
        if( $request->description ){
            $task->description = $request->description;
        }

        if( $request->status ){
            $task->status = $request->status;
        }
        try {
            $task->save();
            return response()->json([
                "id" => $task->id,
                "title" => $task->title,
                "description" => $task->description,
                "assignee_id" => $task->assignee_id,
                "status" => $task->status
            ],200);
        }
        catch (Exception $e){
            return response()->json([
                'Message' => "Data Error Please Try Again",
                'Error' => $e->getMessage()
            ],500);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tasks $tasks)
    {
        //
    }
}
