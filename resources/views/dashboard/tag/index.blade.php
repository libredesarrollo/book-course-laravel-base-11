@extends('dashboard.master')

@section('content')
    @can('editor.tag.create')
        <a class="btn btn-primary my-3" href="{{ route('tag.create') }}" target="blank">Create</a>
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
                    Options
                </th>
            </tr>

        </thead>
        <tbody>
            @foreach ($tags as $t)
                <tr>
                    <td>
                        {{ $t->id }}
                    </td>
                    <td>
                        {{ $t->name }}
                    </td>
                    <td>
                        <a class="btn btn-success mt-2" href="{{ route('tag.show', $t) }}">Show</a>
                        @can('editor.tag.update')
                            <a class="btn btn-success mt-2" href="{{ route('tag.edit', $t) }}">Edit</a>
                        @endcan
                        @can('editor.tag.destroy')
                            <form action="{{ route('tag.destroy', $t) }}" method="post">
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
    {{ $tags->links() }}
@endsection
