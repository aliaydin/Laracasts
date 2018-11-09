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

Route::get('/', 'PageController@home'); // Aşağıdaki tanımlamayı bu şekilde de yapabiliriz.
Route::get('/contact', 'PageController@contact');
Route::get('/about', 'PageController@about');

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

Route::resource('projects', 'ProjectsController'); // Yukarıdaki 7 rota tanımını karşılayan tek rota
Route::PATCH('/tasks/{task}', 'ProjectTasksController@update');
/*
Route::get('/', function () {

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
      'title' => Request('title'),
      'hack' => '<script>alert("Hacked");</script>'
    ]);


    /* methodWay
    return view('welcome')->with([
      'tasks' => $tasks,
      'foo' => 'foobar',
      'title' => Request('title'),
      'hack' => '<script>alert("Hacked");</script>']);
    */
/*
});
*/
/*
Route::get('/contact', function () {
    return view('contact');
});

Route::get('/about', function() {
  return view('about');
});
*/
