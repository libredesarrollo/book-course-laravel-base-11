@extends('dashboard.master')

@section('content')

    @include('dashboard.fragment._errors-form')

   <form action="{{ route('role.store') }}" method="post">
        @include('dashboard.role._form')
   </form>
@endsection