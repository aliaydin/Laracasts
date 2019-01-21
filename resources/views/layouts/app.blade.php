<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title> @yield('title', 'Laracasts')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.2/css/bulma.min.css">
    <style>
        .is-complete {
            text-decoration: line-through;
        }
    </style>
  </head>
  <body>

    <div class="container">

      <ul>
        <li> <a href="{{ url('/') }}">Home</a> </li>
        <li><a href="/contact">Contact Us</a></li>
        <li><a href="/about">About Us</a></li>
        <li><a href="/projects">Projects</a></li>
        </li>
      </ul>

      @yield('content')

    </div>

  </body>
</html>
