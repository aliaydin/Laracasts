@extends('layouts.app')

@section('content')
  <h1>Ana sayfa burası</h1>
  <hr>
  <p> Homepage <b>{{ $foo }}</b> was here! </p>
  <hr>
  <p>phpForeach style</p>

  <ul>
    <?php foreach ($tasks as $task) { ?>
      <li><?= $task; ?> </li>
    <?php } ?>
  </ul>
<hr>
  <p>laravelForeach style</p>

  <ul>
    @foreach($tasks as $task)
      <li> {{ $task }} </li>
    @endforeach
  </ul>
  <hr>
  <p> queryString ile gelen title bilgisi: {{ $title }} Burada echo kullanılırsa alert çalışıyor. <br />
  <hr>
  Hack değişkeni ile gelen bilgi: {{ $hack }}

@endsection
