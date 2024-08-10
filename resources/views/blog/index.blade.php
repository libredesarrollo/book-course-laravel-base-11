@extends('blog.master')

@section('content')
<h1>{{ __('dashboard.title') }}</h1>
    <x-blog.post.index :posts='$posts'>
        Post List

        @slot('footer')
            Footer
        @endslot

        @slot('extra', 'Extra')

    </x-blog.post.index>
@endsection