<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title> @yield('title', 'Laracasts')</title>
  </head>
  <body>
    @yield('content')
    <ul>
      <li>
        <a href="/">Home</a>
        <a href="/contact">Contact Us</a>
        <a href="/about">About Us</a>
      </li>
  </body>
</html>
