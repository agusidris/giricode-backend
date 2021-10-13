<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" href="{{ asset('assets/img/Logo.svg') }}" type="image/x-icon">

  <title>{{ $title ?? config('app.name') }} - Not Found</title>

  <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" media="screen">
  <!-- Bootstrap core JavaScript-->
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

</head>

<body id="page-top">

        <!-- Begin Page Content -->
        @yield('content')
        <!-- /.container-fluid -->
</body>
</html>

