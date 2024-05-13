@extends('dashboard.master')

@section('content')

    <a class="btn btn-primary my-3" href="{{ route('role.create') }}" target="blank">Create</a>

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
            @foreach ($roles as $r)
                <tr>
                    <td>
                        {{ $r->id }}
                    </td>
                    <td>
                        {{ $r->name }}
                    </td>
                    <td>
                        <a class="btn btn-success mt-2" href="{{ route('role.show',$r) }}">Show</a>
                        <a class="btn btn-success mt-2" href="{{ route('role.edit',$r) }}">Edit</a>
                        <form action="{{ route('role.destroy', $r) }}" method="post">
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
    {{ $roles->links() }}

@endsection