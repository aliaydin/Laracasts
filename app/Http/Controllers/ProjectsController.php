<?php

namespace App\Http\Controllers;

use App\Events\ProjectCreated;
// use App\Mail\ProjectCreated; // I named Event same as this. So I need to comment this code.
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

        // Don't Forget Readability
        $projects = auth()->user()->projects; // sadece o kullanıcıya ait projeler.
        // return $projects; // json formatında veriyi gönder.

        // $projects = Project::where('owner_id', auth()->id())->get(); // \app\Project:all(); // Don't Forget Readability lesson remove this line

        // dump($projects); // Simpler Debugging With Laravel Telescope

        // Simpler Debugging With Laravel Telescope
        /*cache()->rememberForever('stats', function() {
           return ['lessons' => 130, 'hours' => 1300, 'series' => 7];
        });*/

        $stats = cache()->get('stats');
        dump($stats);

        return view('projects.index', compact('projects')); // return view('projects.index', ['projects' => $projects]); // default
    }

    // Core Concepts: Service Container and Auto-Resolution
    // public function show(FileSystem $file)

    public function show(Project $project)
    {
        // Authorization Essentials

        // 4. versiyon Route (web.php) içerisinde middleware kullanarak.

        // 3. versiyon Gate Facade
        // abort_if (\Gate::denies('update', $project), 403);
        // abort_unless(\Gate::allows('update', $project), 403);

        // 2. versiyon policy kullanarak
        // $this->authorize('update', $project); // 2. versiyon
        // $this->authorize('view', $project); // methot changed to update



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

        // Don't Forget Readability : Move this code in a method
        /*
        // Validation: validate metodu işini bitirdikten sonra bize alanları döner.
        $validated = request()->validate([ // Eğer bu kısmı geçemezse redirect edecek.
            'title' => ['required', 'min:3'],
            'description' => 'required'
        ]);
        */
        $validated = $this->validateProject();
        $validated['owner_id'] = auth()->id();

        // Simpler Debugging With Laravel Telescope ($project değişkeni mailde kullanılmak için eklendi.)
        $project = Project::create($validated); // 4. Validation sonrası alanları tekrar yollamamak gerekiyor.

        // Custom Events and Listeners : Use eloquent standart model
        // event(new ProjectCreated($project));
        // Project::create(request(['title', 'description'])); // 3. En temiz kod.

        /* // Model Hooks and Seesaws : I mode this code to Project model
        // Simpler Debugging With Laravel Telescope
        \Mail::to($project->owner->email)->send(
            new ProjectCreated($project)
        );
        */
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
        // Don't Forget Readability
        $validated = $this->validateProject();
        $project->update($validated);

        // Don't Forget Readability : Use $validated approach
        // Project::update(request(['title', 'description'])); // 2. yöntem tek satır ve en temiz kod.

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

    public function validateProject()
    {
        return request()->validate([
            'title' => ['required', 'min:3'],
            'description' => 'required'
        ]);
    }

}
