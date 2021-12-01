<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Project;
use App\Models\ProjectAccess;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Kanban;
use Lang;

class KanbanController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'title' => 'required',
            'project_id' => 'required',
            'status' => 'required'
        ]);

        $createTask = Kanban::create($request->all());

        return response()->json(['success'=>Lang::get('kanban.task_added')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::find($id);

        $kanbanData = [];

        $notStarted = Kanban::where('project_id','=',$id)
                    ->where('status','=','0')->get();
        $inProgress = Kanban::where('project_id','=',$id)
                    ->where('status','=','1')->get();
        $unitTesting = Kanban::where('project_id','=',$id)
                    ->where('status','=','2')->get();
        $completed = Kanban::where('project_id','=',$id)
                    ->where('status','=','3')->get();
        
        $kanbanData = [
            'notStarted' => $notStarted,
            'inProgress' => $inProgress,
            'unitTesting' => $unitTesting,
            'completed' => $completed
        ];

        return view('kanban.index', compact('kanbanData'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updateStatus(Request $request)
    {
        $taskDetail = Kanban::find($request->task_id);

        $taskDetail->status = $request->current;
        $res = $taskDetail->save();

        if ($res) {
            return response()->json(['success'=>Lang::get('kanban.update_success')]);
        } else {
            return response()->json(['error'=>Lang::get('kanban.update_error')]);
        }
    }
}
