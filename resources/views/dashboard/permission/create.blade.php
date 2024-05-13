@extends('dashboard.master')

@section('content')

    @include('dashboard.fragment._errors-form')

   <form action="{{ route('permission.store') }}" method="post">
        @include('dashboard.permission._form')
   </form>
@endsection