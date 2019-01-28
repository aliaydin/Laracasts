<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title> @yield('title', 'Laracasts')</title>
    <style>
        .is-complete {
            text-decoration: line-through;
        }
    </style>
  </head>
  <body>

    <div id="menu" class="mt-3">
      <ul>
        <li style="display: inline;"> <a href="{{ url('/') }}">Home</a> </li>
        <li style="display: inline;"><a href="{{ url('/contact') }}">Contact Us</a></li>
        <li style="display: inline;"><a href="{{ url('/about') }}">About Us</a></li>
        <li style="display: inline;"><a href="{{ url('/projects') }}">Projects</a></li>
      </ul>


    </div>
    <div class="container mt-5">

      @yield('content')

    </div>

  </body>
</html>
