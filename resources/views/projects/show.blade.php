@extends('layouts.app')

@section('content')

    <h2> {{ $project->title }}</h2>

    <div class="content"> {{ $project->description }} </div>
    <p>
        <a href="{{ url('projects/' . $project->id) }}/edit">Edit</a>
    </p>

    @if ($project->tasks->count())
        <div class="box">
            @foreach($project->tasks as $task)
                <div>
                    <form method="post" action="{{ url('tasks/'. $task->id) }}">
                        @method('PATCH')
                        @csrf
                        <div class="{{ $task->completed ? 'is-complete' : '' }}">
                            <input type="checkbox" name="completed" onchange="this.form.submit()" {{ $task->completed ? 'checked' : '' }}>
                            {{ $task->description }}
                        </div>
                    </form>
                </div>
            @endforeach
        </div>
    @endif

    <form class="box" method="post" action="{{ url('projects/' . $project->id . '/tasks') }}" >
        @csrf
        <div>
            New Task <input type="text" name="description" placeholder="New Task">
            <button type="submit">Add Task</button>
        </div>

        @include('errors')

    </form>

@endsection
