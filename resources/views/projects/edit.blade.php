@extends('layouts.app')

@section('content')

    <h2>Edit Project</h2>

      <form action="{{ url('projects/' . $project->id) }}" method="post" style="margin-bottom: 1em">

        {{ method_field('PATCH') }}
        {{ csrf_field() }}

        <div class="">
          <input type="text" name="title" placeholder="Project title" value="{{ $project->title }}">
        </div>

        <div class="">
          <textarea name="description" rows="8" cols="80" placeholder="Project description">{{ $project->description }}</textarea>
        </div>

        <div class="">
          <button type="submit" name="button">Update Project</button>

        </div>

      </form>

      @include('errors')

      <form action="{{ url('projects/' . $project->id) }}" method="post">

        @method('DELETE')
        @csrf

        <div class="">
          <button type="submit" name="button">Delete Project</button>
        </div>

      </form>
@endsection
