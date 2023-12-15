<?php

namespace App\Http\Controllers;

use App\Models\Projects;
use App\Models\Tasks;
use App\myClass\Process;
use App\Repositories\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    private $project;

    public function __construct()
    {
        $this->project = new Project(new Projects);

    }

    public function getProjects()
    {
        $authuser = auth()->user()->id;

        $projects = Projects::where('admin', $authuser)
            ->orwhere('members', 'like', "%$authuser%")
            ->with(['task:project_id,state'])->get();

        return response()->json(["projects" => $projects]);
    }

    public function getProject(Request $request)
    {
        $project = Projects::with(["user", "task"])->firstWhere("id", $request->id);
        return response()->json(["project" => $project], 200);
    }

    public function store(Request $request)
    {
        if (count($request->members) > 0) {
            $members = '|';
            foreach ($request->members as $member) {
                $members .= $member . '|';
            }
            $request['members'] = $members;

        } else $request['members'] = null;

        return $this->project->create($request);
    }

    public function update(Request $request)
    {
        return $this->project->update($request);
    }
}
