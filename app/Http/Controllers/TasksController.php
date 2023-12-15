<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use App\Models\UserTasks;
use App\Repositories\Project;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    private $task;

    public function __construct()
    {
        $this->task = new Project(new Tasks);
    }

    public function user_list(Request $request)
    {
        $users = UserTasks::where('task_id', $request->id)->with('user')->get();
        $u = [];
        foreach ($users as $user) {
            $u[] = ['id' => $user->user->id, 'name' => $user->user->name];
        }
        return response()->json(['list' => $u]);
    }

    public function getTask(Request $request)
    {
        $task = Tasks::with(["myparents", "subTask", 'document', 'category'])->firstWhere("id", $request->id);
        foreach ($task->document as $key => $doc) {
            $file = base_path('public/storage/asset/' . $doc->system_name);
            $task->document[$key]->mime = mime_content_type($file);
        }

        return response()->json(["task" => $task]);
    }

    public function store_task(Request $request)
    {
        $myTask = $this->task->create($request);

        if ($request->user_list) {
            foreach ($request->user_list as $user) {
                UserTasks::create([
                    'task_id' => $myTask->id,
                    'user_id' => $user
                ]);
            }
        }
        $myTask["sub_task"] = [];
        return response()->json(['message' => "success", 'taskData' => $myTask]);
    }

    public function update_state(Request $request)
    {
        return $this->task->update($request);
    }

    public function delete(Request $request)
    {
        $id = Tasks::firstWhere('id', $request->id);
        if ($id) {
            $id->delete();
            return ['message' => 'delete successfully', 'data' => $id];
        }
    }
}
