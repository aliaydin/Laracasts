@extends('layout')

@section('content')

    <h2> {{ $project->title }}</h2>

    <div class="content">{{ $project->description }}<div>
    <p>
        <a href="/projects/{{ $project->id }}/edit">Edit</a>
    </p>

    @if ($project->tasks->count())
        <div>
            @foreach($project->tasks as $task)
                <form method="post" action="/tasks/{{ $task->id }}">
                    @method('PATCH')
                    @csrf
                    <div class="{{ $task->completed ? 'is-complete' : '' }}">
                        <input type="checkbox" name="completed" onchange="this.form.submit()" {{ $task->completed ? 'checked' : '' }}>
                        {{ $task->description }}
                    </div>
                </form>
            @endforeach
        </div>
    @endif

@endsection
