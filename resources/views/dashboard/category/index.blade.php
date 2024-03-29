@extends('dashboard.master')

@section('content')

    <a class="btn btn-primary my-3" href="{{ route('category.create') }}" target="blank">Create</a>

    <table class="table">
        <thead>
            <tr>
                <th>
                    Id
                </th>
                <th>
                    Title
                </th>
                <th>
                    Options
                </th>
            </tr>
            
        </thead>
        <tbody>
            @foreach ($categories as $c)
                <tr>
                    <td>
                        {{ $c->id }}
                    </td>
                    <td>
                        {{ $c->title }}
                    </td>
                    <td>
                        <a class="btn btn-success mt-2" href="{{ route('category.show',$c) }}">Show</a>
                        <a class="btn btn-success mt-2" href="{{ route('category.edit',$c) }}">Edit</a>
                        <form action="{{ route('category.destroy', $c) }}" method="post">
                            @method('DELETE')
                            @csrf
                            <button class="btn btn-danger mt-2" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-2"></div>
    {{ $categories->links() }}

@endsection