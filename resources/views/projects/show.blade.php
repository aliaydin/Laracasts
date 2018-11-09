@extends('layout')

@section('content')

    <h2> {{ $project->title }}</h2>
    <p>
        <a href="/projects/{{ $project->id }}/edit">Edit</a>
    </p>

    <div class="content">{{ $project->description }}<div>
    @if ($project->tasks->count())
        <div>
            <ul>
                @foreach($project->tasks as $task)
                    <li>{{ $task->description }} </li>
                @endforeach
            </ul>
        </div>
    @endif

@endsection
