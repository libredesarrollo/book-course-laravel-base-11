@extends('dashboard.master')

@section('content')

    @include('dashboard.fragment._errors-form')

   <form action="{{ route('user.store') }}" method="post">
        @include('dashboard.user._form')
   </form>
@endsection