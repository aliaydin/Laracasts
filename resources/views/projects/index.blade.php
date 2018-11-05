@extends('layout')

@section('content')

    <h1> Projects </h1>

      @foreach ($projects as $project)
        <li> {{ $project->title }} </li>
      @endforeach

@endsection
