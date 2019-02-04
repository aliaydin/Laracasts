@extends('layouts.app')

@section('content')

    <h1> Projects </h1>
    <ul>

      @foreach ($projects as $project)

        <a href="{{ url('projects/' . $project->id) }}">
          <li> {{ $project->title }} </li>
        </a>
      @endforeach
    </ul>
@endsection
