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
}
