@extends('layout')

@section('content')

    <h1> Projects </h1>
    <ul>

      @foreach ($projects as $project)

        <a href="/projects/{{ $project->id }}">
          <li> {{ $project->title }} </li>
        </a>
      @endforeach
    </ul>
@endsection
