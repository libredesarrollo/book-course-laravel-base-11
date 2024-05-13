@extends('dashboard.master')

@section('content')
    @can('editor.user.create')
        <a class="btn btn-primary my-3" href="{{ route('user.create') }}" target="blank">Create</a>
    @endcan

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
                    Email
                </th>
                <th>
                    Role
                </th>
                <th>
                    Options
                </th>
            </tr>

        </thead>
        <tbody>
            @foreach ($users as $u)
                <tr>
                    <td>
                        {{ $u->id }}
                    </td>
                    <td>
                        {{ $u->name }}
                    </td>
                    <td>
                        {{ $u->email }}
                    </td>
                    <td>
                        {{ $u->rol }}
                    </td>
                    <td>
                        <a class="btn btn-success mt-2" href="{{ route('user.show', $u) }}">Show</a>
                        @can('editor.user.update')
                            <a class="btn btn-success mt-2" href="{{ route('user.edit', $u) }}">Edit</a>
                        @endcan
                        @can('editor.user.destroy')
                            <form action="{{ route('user.destroy', $u) }}" method="post">
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
    {{ $users->links() }}
@endsection
