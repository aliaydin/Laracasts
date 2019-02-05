@extends('layouts.app')

@section('content')

    <h1> Projects </h1>
    <ul>

        @foreach ($projects as $project)
            <li>
                <a href="{{ url('projects/' . $project->id) }}">
                    {{ $project->title }}
                </a>
            </li>
        @endforeach
    </ul>
@endsection
