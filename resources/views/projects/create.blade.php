@extends('layout')

@section('content')
    <h2>Create a New Project</h2>

      <form class="" action="/projects" method="post">

        {{ csrf_field() }}

        <div class="">
          <input type="text" name="title" placeholder="Project title">
        </div>

        <div class="">
          <textarea name="description" rows="8" cols="80" placeholder="Project description"></textarea>
        </div>

        <div class="">
          <button type="submit" name="button">Create Project</button>
        </div>

      </form>

@endsection
