<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\FileSystem\FileSystem;

class ProjectsController extends Controller
{


    public function __construct()
    {
        // You May Only View Your Projects

        // $this->middleware('auth');
        // $this->middleware('auth')->only(['store', 'destroy']);
        $this->middleware('auth')->except(['show']); // Sadece show herkese açık olsun şimdilik

    }

    public function index()
    {

        // auth()->user()->projects; // sadece o kullanıcıya ait projeler.??
        // return $projects; // json formatında veriyi gönder.

        $projects = Project::where('owner_id', auth()->id())->get(); // \app\Project:all();
        return view('projects.index', compact('projects')); // return view('projects.index', ['projects' => $projects]); // default
    }

    // Core Concepts: Service Container and Auto-Resolution
    // public function show(FileSystem $file)

    public function show(Project $project)
    {
        // Authorization Essentials
        // 2. versiyon policy kullanarak
        $this->authorize('view', $project);




        // 1. versiyon direkt elle kontrol
        /*if ($project->owner_id != auth()->id()) { // abort_if ile daha kısasını yazacağım.
            abort(403);
        }*/
        // abort_if($project->owner_id != auth()->id(), 403); // 1. versiyon, kısa hali


        return view('projects.show', compact('project'));
        //return view('projects.show', compact('project'));
    }

    /*
    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
        //return view('projects.show', compact('project'));
    }*/

    public function create()
    {
        return view('projects.create');
    }

    public function store()
    {

        // Validation: validate metodu işini bitirdikten sonra bize alanları döner.
        $validated = request()->validate([ // Eğer bu kısmı geçemezse redirect edecek.
            'title' => ['required', 'min:3'],
            'description' => 'required'
        ]);

        $validated['owner_id'] = auth()->id();
        Project::create($validated); // 4. Validation sonrası alanları tekrar yollamamak gerekiyor.

        // Project::create(request(['title', 'description'])); // 3. En temiz kod.

        return redirect('projects');

        /*
        // Cleaner Controllers and Mass Assignment Concerns : 2. ve request in array özelliğini kullanmayan yol
        Project::create([
            'title' => request('title'),
            'description' => request('description')
        ]);
        */

        /*
        // Form Handling and CSRF Protection // 1 En uzun yol Tabi burada mass assignment hatası asla olmaz.
        $project = new Project();

        $project->title = request('title');
        $project->description = request('description');

        $project->save();
        */

        /*


                // Project::create(request()->all()); // Bu kullanılacaksa Project model de $fillable tanımlanmalı.


        */

    }

    // Faking PATCH and DELETE Requests
    public function edit(Project $project) // example.com/projects/{id}/edit
    {

        // Faking PATCH and DELETE Requests
        // $project = Project::findOrFail($id); // edit update destroy ve show için bu işlem yapıldı. $id gitti Project $project geldi.

        return view('projects.edit', compact('project')); // ControllerName.ViewName
    }

    public function update(Project $project) //(Project $project)
    {
        Project::update(request(['title', 'description'])); // 2. yöntem tek satır ve en temiz kod.

        return redirect('/projects'); // Normalde burada show a gitmesi gerekir. Şimdilik böyle

        // ## Faking PATCH and DELETE Requests ##
        // $project = Project::find($id);
        /*
        // Cleaner Controllers and Mass Assignment Concerns 1. yöntem biraz uzun
        $project->title = request('title');
        $project->description = request('description');

        $project->save();
        */

    }

    // Form Delete Requests
    public function destroy(Project $project)
        //(Project $project)
    {
        // Form Delete Requests
        // Project::find($id)->delete();

        // Cleaner Controllers and Mass Assignment Concerns
        $project->delete();

        return redirect(route('projects.index'));

        /*
        $project->delete();

        return redirect('/projects'); */
    }


}
