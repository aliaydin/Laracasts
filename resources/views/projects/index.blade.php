@extends('layout')

@section('content')
    <h2>Projects</h2>
      @foreach ($projects as $project)
        <li> {{ $project->title }} </li>
      @endforeach
@endsection
