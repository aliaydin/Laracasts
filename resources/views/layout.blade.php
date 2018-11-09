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
        <li>
          <a href="/">Home</a>
          <a href="/contact">Contact Us</a>
          <a href="/about">About Us</a>
          <a href="/projects">Projects</a>
        </li>
      </ul

      @yield('content')

    </div>

  </body>
</html>
