@extends('dashboard.master')

@section('content')

    @include('dashboard.fragment._errors-form')

   <form id="myForm" action="{{ route('post.store') }}" method="post">
        @include('dashboard.post._form')
   </form>
@endsection