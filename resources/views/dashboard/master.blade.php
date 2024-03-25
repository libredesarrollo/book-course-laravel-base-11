<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
</head>
<body>
    {{-- <header>
        Header 2
    </header> --}}
    
    {{-- @session('status')
        {{ $value }}
    @endsession --}}
    {{-- @session('key')
        <h1>{{ $value }}</h1>
    @endsession
    @session('key2')
        <h1>{{ $value }}</h1>
    @endsession --}}

    @if (session('status'))
        {{ session('status') }}
    @endif

    @yield('content')

   <section>
        @yield('morecontent')
   </section>

</body>
</html>