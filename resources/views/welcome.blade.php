@extends('layouts.app')

@section('content')
  <h1>Ana sayfa burası</h1>
  <p> Homepage <b>{{ $foo }}</b> was here! </p>
  <ul>

    <?php foreach ($tasks as $task) { ?>
      <li><?= $task; ?> </li>
    <?php } ?>
  </ul>


  <ul>
    @foreach($tasks as $task)
      <li> {{ $task }} </li>
    @endforeach
  </ul>

  <p> queryString ile gelen title bilgisi: {{ $title }} Burada echo kullanılırsa alert çalışıyor. <br /> 
  Hack değişkeni ile gelen bilgi: {{ $hack }}
@endsection
