@extends('blog.master')

@section('content')
    <br><br>
    <x-blog.show :post="$post" />
@endsection