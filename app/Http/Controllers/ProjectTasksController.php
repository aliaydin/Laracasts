<?php

namespace App\Http\Controllers;

use App\Task;
use App\Project;

use Illuminate\Http\Request;

class ProjectTasksController extends Controller
{

    // Create New Project Tasks
    public function store(Project $project) {

        // 1. yaklaşım. Bu klasik yaklaşım ama burada koda bakan adam project ve task arasındaki bağlantıyı göremiyor.
        /*
        Task::create([
            'project_id' => $project->id,
            'description' => request('description')
        ]);
        */


        // 2. yaklaşım. Projeye task eklenecekse bunu Project altındaki addTask ile yapmalıyız.
        $attributes = request()->validate(['description' => 'required|min:5']); // doğrulama eklendi.
        // $project->addTask(request('description')); // id bilgisi zaten $this->id de saklı.
        $project->addTask($attributes); // id bilgisi zaten $this->id de saklı.

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
