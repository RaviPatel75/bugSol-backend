<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
// use App\Http\Controllers\DB;
use DB;

class ProjectController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:project-list|project-create|project-edit|project-delete', ['only' => ['index','show']]);
        $this->middleware('permission:project-create', ['only' => ['create','store']]);
        $this->middleware('permission:project-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:project-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::latest()->paginate(5);
        return view('projects.index',compact('projects'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userId = Auth::user()->id;
        $users = User::where('created_by','=',$userId)->pluck('name','id');
        return view('projects.create',compact('users'));
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
            'name' => 'required',
        ]);

        // dd($usersAssigned);
        $createdProject = Project::create($request->all());
        $projectId = $createdProject->id;
        $usersAssigned = array_merge(array(Auth::user()->id), $request->users);

        foreach($usersAssigned as $assignUser)
        {
            $projectAccessData = array(
                'project_id' => $projectId,
                'user_id' => $assignUser
            );

            ProjectAccess::create($projectAccessData);

            unset($projectAccessData);
        }

        return redirect()->route('project.index')
                        ->with('success','Project created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        $users = ProjectAccess::leftjoin('users',function($join) {
                                    $join->on('project_access.user_id','=','users.id');
                                })
                                ->where('project_access.project_id','=',$project->id)
                                ->pluck('users.name');

        return view('projects.show',compact('project','users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $users = User::where('created_by','=',Auth::user()->id)->pluck('name','id');
        $assignedUsers = ProjectAccess::leftjoin('users',function($join) {
                                    $join->on('project_access.user_id','=','users.id');
                                })
                                ->where('project_access.project_id','=',$project->id)
                                ->pluck('users.id');

        return view('projects.edit',compact('project','users','assignedUsers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        request()->validate([
            'name' => 'required',
        ]);
    
        $projectId = $project->id;
        $project->update($request->all());
        
        ProjectAccess::where('project_id',$projectId)->delete();
        $usersAssigned = array_merge(array(Auth::user()->id), $request->users);

        foreach($usersAssigned as $assignUser)
        {
            $projectAccessData = array(
                'project_id' => $projectId,
                'user_id' => $assignUser
            );

            ProjectAccess::create($projectAccessData);

            unset($projectAccessData);
        }
    
        return redirect()->route('project.index')
                        ->with('success','Project updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $projectId = $project->id;
        $project->delete();

        ProjectAccess::where('project_id',$projectId)->delete();
    
        return redirect()->route('project.index')
                        ->with('success','Project deleted successfully');
    }
}
