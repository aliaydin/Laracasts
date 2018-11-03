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
Route::get('/projects', 'ProjectsController@index');

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
