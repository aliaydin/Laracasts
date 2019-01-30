@extends('layouts.app')

@section('content')
    <h2>Create a New Project</h2>

      <form method="POST" action="{{ url('/projects') }}">

        {{ csrf_field() }}

        <div class="">
          <input type="text" name="title" placeholder="Project title" required class="{{ ($errors->has('title')) ? 'is-danger' : '' }}" value="{{ old('title') }}">
        </div>

        <div class="">
          <textarea name="description" rows="8" cols="80" required placeholder="Project description">{{ old('description') }}</textarea>
        </div>

        <div class="">
          <button type="submit">Create Project</button>
        </div>

        @if ($errors->any())
          <div class="notification is-danger">
            <ul>
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

      </form>

@endsection
