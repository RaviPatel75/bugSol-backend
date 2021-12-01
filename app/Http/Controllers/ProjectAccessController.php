<?php

namespace App\Http\Controllers;

use App\Models\ProjectAccess;
use Illuminate\Http\Request;

class ProjectAccessController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index','show']]);
        // $this->middleware('permission:product-create', ['only' => ['create','store']]);
        // $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:product-delete', ['only' => ['destroy']]);
        $this->middleware('permission:project-assign', ['only' => ['index']]);
    }
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectAccess  $projectAccess
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectAccess $projectAccess)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProjectAccess  $projectAccess
     * @return \Illuminate\Http\Response
     */
    public function edit(ProjectAccess $projectAccess)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProjectAccess  $projectAccess
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProjectAccess $projectAccess)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectAccess  $projectAccess
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProjectAccess $projectAccess)
    {
        //
    }
}
