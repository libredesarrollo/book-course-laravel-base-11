@extends('dashboard.master')

@section('content')

    @include('dashboard.fragment._errors-form')

   <form action="{{ route('post.store') }}" method="post">
        @include('dashboard.post._form')
   </form>
@endsection