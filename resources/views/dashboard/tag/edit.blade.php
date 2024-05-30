@extends('dashboard.master')

@section('content')

    @include('dashboard.fragment._errors-form')

   <form action="{{ route('tag.update', $tag->id) }}" method="post">
        @method('PATCH')
        @include('dashboard.tag._form', [ 'task'=>'edit' ])
   </form>
@endsection