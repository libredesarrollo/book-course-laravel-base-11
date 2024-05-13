@extends('dashboard.master')

@section('content')

    @include('dashboard.fragment._errors-form')

   <form action="{{ route('permission.update', $permission->id) }}" method="post">
        @method('PATCH')
        @include('dashboard.permission._form', [ 'task'=>'edit' ])
   </form>
@endsection