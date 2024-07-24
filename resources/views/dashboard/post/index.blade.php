@extends('dashboard.master')

@section('content')

    @can('editor.post.create')
        <a class="btn btn-primary my-3" href="{{ route('post.create') }}" target="blank">Create</a>
    @endcan

    {{__('dashboard.welcome',['name' => ucfirst('andres')])}}
    {{__('dashboard.welcome',['name' => 'andres'])}}

    <table class="table">
        <thead>
            <tr>
                <th>
                    Id
                </th>
                <th>
                    {{ __('dashboard.title') }}
                </th>
                <th>
                    {{ __('dashboard.posted') }}
                </th>
                <th>
                    {{ __('dashboard.category') }}
                </th>
                <th>
                    {{ __('dashboard.options') }}
                </th>
            </tr>
            
        </thead>
        <tbody>
            @foreach ($posts as $p)
                <tr>
                    <td>
                        {{ $p->id }}
                    </td>
                    <td>
                        {{ $p->title }}
                    </td>
                    <td>
                        {{ $p->posted }}
                    </td>
                    <td>
                        {{ $p->category->title }}
                    </td>
                    <td>
                        <a class="btn btn-success mt-2" href="{{ route('post.show',$p) }}">Show</a>

                        @can('editor.post.update')
                            <a class="btn btn-success mt-2" href="{{ route('post.edit',$p) }}">Edit</a>
                        @endcan
                        @can('editor.post.destroy')
                        <form action="{{ route('post.destroy', $p) }}" method="post">
                            @method('DELETE')
                            @csrf
                            <button class="btn btn-danger mt-2" type="submit">Delete</button>
                        </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-2"></div>
    {{ $posts->links() }}

@endsection