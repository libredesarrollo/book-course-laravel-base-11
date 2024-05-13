@extends('dashboard.master')

@section('content')

    <a class="btn btn-primary my-3" href="{{ route('permission.create') }}" target="blank">Create</a>

    <table class="table">
        <thead>
            <tr>
                <th>
                    Id
                </th>
                <th>
                    Name
                </th>
                <th>
                    Options
                </th>
            </tr>
            
        </thead>
        <tbody>
            @foreach ($permissions as $p)
                <tr>
                    <td>
                        {{ $p->id }}
                    </td>
                    <td>
                        {{ $p->name }}
                    </td>
                    <td>
                        <a class="btn btn-success mt-2" href="{{ route('permission.show',$p) }}">Show</a>
                        <a class="btn btn-success mt-2" href="{{ route('permission.edit',$p) }}">Edit</a>
                        <form action="{{ route('permission.destroy', $p) }}" method="post">
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
    {{ $permissions->links() }}

@endsection