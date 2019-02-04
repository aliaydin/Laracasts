<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;

use Illuminate\FileSystem\FileSystem;

class ProjectsController extends Controller
{
    public function show(FileSystem $file)
    {
        return view('projects.show', compact('project'));
    }

    //
    public function index()
    {
        // auth()->user()->projects; // sadece o kullanıcıya ait projeler.??
        // return $projects; // json formatında veriyi gönder.

        $projects = Project::all(); // \app\Project:all();
        return view('projects.index', compact('projects')); // return view('projects.index', ['projects' => $projects]); // default


    }

    public function create()
    {
        return view('projects.create');
    }

    public function store()
    {
        /*
                // Validation: validate metodu işini bitirdikten sonra bize alanları döner.
                $validated = request()->validate([
                    'title' => ['required', 'min:3'],
                    'description' => 'required'
                ]);

                // Project::create(request()->all()); // Bu kullanılacaksa Project model de $fillable tanımlanmalı.

                Project::create($validated); // request yollamana gerek yok alanlar $validate ten geliyor.
        */
        //Project::create(request(['title', 'description'])); // En temiz kod.

        /* // 2. ve request in array özelliğini kullanmayan yol
              Project::create([
                'title' => request('title'),
                'description' => request('description')
              ]);*/


        // Form Handling and CSRF Protection // İlk ve en uzun yol
        $project = new Project();

        $project->title = request('title');
        $project->description = request('description');

        $project->save();

        return redirect('projects');
    }

    // Faking PATCH and DELETE Requests
    public function edit($id) // example.com/projects/{id}/edit
    {
        // Faking PATCH and DELETE Requests
        $project = Project::findOrFail($id); // edit update destroy ve show için bu işlem yapıldı. $id gitti Project $project geldi.

        return view('projects.edit', compact('project')); // ControllerName.ViewName
    }

    public function update($id) //(Project $project)
    {
        // ## Faking PATCH and DELETE Requests ##
        $project = Project::find($id);

        $project->title = request('title');
        $project->description = request('description');

        $project->save();

        return redirect('/projects'); // Normalde burada show a gitmesi gerekir. Şimdilik böyle

        // Project::update(request(['title', 'description'])); // En temiz kod.

        /*
              $project->title = request('titpublic function show(Project $project) {le');
              $project->description = request('description');

              $project->save();
        */



    }

    // Form Delete Requests
    public function destroy($id)
        //(Project $project)
    {
        // Form Delete Requests
        Project::find($id)->delete();
        return redirect(route('projects.index'));

        /*
        $project->delete();

        return redirect('/projects'); */
    }


}
