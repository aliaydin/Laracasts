<?php

namespace App\Http\Controllers;

use App\Task;
use App\Project;

use Illuminate\Http\Request;

class ProjectTasksController extends Controller
{

    public function store(Project $project) {
/*
        Task::create([
            'project_id' => $project->id,
            'description' => request('description')
        ]);
*/
        $attributes = request()->validate(['description' => 'required']);

        $project->addTask($attributes);

        return back();
    }


    // Form Action Considerations
    // CompletedTasksController eklendikten sonra gerek kalmadı.
    public function update(Task $task) { // Route Model Binding

        /*
        $method = request()->has('completed') ? 'complete' : 'incomplete';
        $task->$method();
*/
        /*
        request()->has('completed') ? $task->complete() : $task->incomplete();
        $task->complete(request()->has('completed'));
*/
        // /* encapsulation refactor 1
        $task->update([
            'completed' => request()->has('completed')
        ]);

        return back();
    }

}
