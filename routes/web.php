<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*app()->singleton('example', function() {
    return new \App\Example;
});
*/

use App\Services;
use App\Services\Twitter;
use Illuminate\Filesystem\Filesystem;
use App\Repositories\UserRepository;

Auth::routes();

// Core Concepts: Service Container and Auto-Resolution
app()->bind('example', function () {
    return new \App\Example();
});

// Core Concepts: Service Container and Auto-Resolution
app()->singleton("twitter", function () {
    dd("twitter app le bağlı singleton dan sesleniyor");
    return new App\Services\Twitter('api_key'); // config('services.twitter.api_key');
});

// Core Concepts: Service Container and Auto-Resolution
/*
Route::get('/', function (Twitter $twitter) {
    dd($twitter);
    // dd(app('\App\Example'));
    // dd(app(Filesystem::class));
});
*/

// Core Concepts: Service Providers
Route::get('/', function (Twitter $twitter) {

    // CoreConcepts_ConfigurationAndEnvironments
    // dd($twitter);
    // Core Concepts: Service Providers
    // dd(app('foo'));
    // A Full Registration System in Seconds
    return view('home');

});


/*
// Basic Routing
Route::get('/', function () {
// Sending Data to Your Views
    $tasks = [
        'Go to the store',
        'Go to the market',
        'Go to work',
        'Go to the concert'
    ];

    // return view('welcome')->withTasks($tasks)->withFoo('foo');
    return view('welcome', [
        'tasks' => $tasks,
        'foo' => 'foobar',
        'title' => Request('title'), // Request burada queryStringle gelen bilgiyi temsil ediyor.
        'hack' => '<script>alert("Hacked");</script>'
    ]);


    /* methodWay // method yaklaşımı çok kullanılmıyor. Genelde kullanılan metoda 2. parametre olarak vermek.
    return view('welcome')->with([
      'tasks' => $tasks,
      'foo' => 'foobar',
      'title' => Request('title'),
      'hack' => '<script>alert("Hacked");</script>']);
    */

/* }); */


// Controllers 101
Route::get('/contact', 'PageController@contact'); // Normalde bu şekilde gelen istekler controller lara gönderilmelidir.

/*// Basic Routing // php artisan route:list komutunda hataya neden oluyordu.
Route::get('/about', function() { // Bu yaklaşım statik ve bussiness layer olmayan projeler için ideal.
    return view('about');
}); */

Route::get('/about', 'PageController@about');

// Eloquent, Namespacing, and MVC
// Route::get('/projects', 'ProjectsController@index');


/*
Route::get('/', function (Twitter $twitter) {
    dd($twitter);
    $twitter = app('Twitter');
    dd($twitter);
    dd(app('App\Example'));
});
*/
//Route::get('/', 'PageController@home'); // Aşağıdaki tanımlamayı bu şekilde de yapabiliriz.


/*

  GET /projects (index) // Tüm kayıtları listele
  GET /projects/create (create) // Kullanıcıya bilgileri gireceği bir form gösterir
  GET /projects/1 (show) // Bir kaydı gösterir
  POST /projects (store) // Yeni bir kayıt oluştur. Bunu çağırmak için öncesinde bir form göstermek gerekir.
  GET /projects/1/edit (edit) // Kullanıcıya bilgileri güncelleyebileceği bir form gösterir
  PATCH /projects/1 (update) // Bir kaydı günceller
  DELETE /projects/1 (destroy) // Bir kaydı siler

Yukarıdaki tanımlamarın implemente edilmiş hali

Route::get('/projects', 'ProjectsController@index');
Route::get('/projects/create', 'ProjectsController@create');
Route::get('/projects/{project}', 'ProjectsController@show');
Route::post('/projects', 'ProjectsController@store');
Route::get('/projects/{project}/edit', 'ProjectsController@edit');
Route::patch('/projects/{project}', 'ProjectsController@update');
Route::delete('/projects/{project}', 'ProjectsController@destroy');
*/

// // You May Only View Your Projects ->middleware('auth')
// Route::resource('projects', 'ProjectsController'); // Yukarıdaki 7 rota tanımını karşılayan tek rota

// Authorization Essentials
Route::resource('projects', 'ProjectsController')->middleware('can:update,project');

// Form Handling and CSRF Protection
// Route::get('/projects/create', 'ProjectsController@create');
// Route::post('/projects', 'ProjectsController@store');

// Form Action Considerations
Route::patch('/tasks/{task}', 'ProjectTasksController@update');

// Create New Project Tasks
// Route::post('/tasks', 'ProjectTasksController@store'); // Kısa versiyonu tercih etmiyorum.
Route::post('/projects/{project}/tasks', 'ProjectTasksController@store');

// Route::patch('/tasks/{task}', 'ProjectTasksController@update'); // CompletedTasksController geldi
Route::post('/completed-tasks/{task}', 'CompletedTasksController@store');
Route::delete('/completed-tasks/{task}', 'CompletedTasksController@destroy');
Auth::routes();


// Core Concepts: Middleware
// Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');

// Core Concepts: Middleware
Route::get('singup', 'HomeController@singup')->middleware('guest');

