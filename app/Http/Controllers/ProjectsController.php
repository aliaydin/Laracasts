<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;

class ProjectsController extends Controller
{
    //
    public function index() {

      $projects = Project::all(); // \app\Project:all();
      // return $projects; // json formatında veriyi gönder.
      // return view('projects.index', ['projects' => $projects]); // default
      return view('projects.index', compact('projects'));

    }

    public function create() {

      return view('projects.create');

    }

    public function store() {

      $project = new Project();

      $project->title = request('title');
      $project->description = request('description');

      $project->save();

      return redirect('projects');

    }

    public function edit($id) {

      $project = Project::find($id);

      return view('projects.edit', compact('project'));

    }

    public function update($id) {

      $project = Project::find($id);

      $project->title = request('title');
      $project->description = request('description');

      $project->save();

      return redirect('/projects'); // Normalde burada show a gitmesi gerekir. Şimdilik böyle

    }
}
