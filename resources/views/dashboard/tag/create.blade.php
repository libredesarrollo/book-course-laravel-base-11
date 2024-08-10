@extends('dashboard.master')

@section('content')

    @include('dashboard.fragment._errors-form')

   <form action="{{ route('tag.store') }}" method="post">
        @include('dashboard.tag._form')
   </form>
@endsection