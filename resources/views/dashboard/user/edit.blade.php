@extends('dashboard.master')

@section('content')

    @include('dashboard.fragment._errors-form')

   <form action="{{ route('user.update', $user->id) }}" method="post">
        @method('PATCH')
        @include('dashboard.user._form', [ 'task'=>'edit' ])
   </form>
@endsection