@extends('layout')

@section('content')

    <h2> {{ $project->title }}</h2>

    <div class="content">{{ $project->description }}<div>
      <p>
        <a href="/projects/{{ $project->id }}/edit">Edit</a>
      </p>

@endsection
