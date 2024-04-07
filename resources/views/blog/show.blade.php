@extends('blog.master')

@section('content')

<br><br>
    {{-- <x-card info='success'>
      asasas
    </x-card> --}}
    {{-- <x-card class="bg-yellow-600" :bg="false">
        Contneido dinamico
    </x-card> --}}

    <x-blog.show :post="$post" />

    <x-dynamic-component component='blog.show' :post="$post" />

    {{-- <div class="card card-white">
        <h1>{{ $post->title }}</h1>
        <span>{{ $post->category->title }}</span>

        <hr>

        {{ $post->content }}
    </div> --}}
@endsection