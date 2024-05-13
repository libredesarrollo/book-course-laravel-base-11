<div>
    <div class="card card-gray">
        <h3>Permission</h3>
        <ul id="permissionListRol">
            @foreach ($permissionsRole as $p)
                <li class="per_{{ $p->id }} item-list">
                    {{-- <form action="{{ route('role.delete.permission', $role->id) }}" method="post">
                    @method('delete')
                    @csrf
                    <input type="hidden" name="permission" value="{{ $p->id }}"> --}}
                    {{ $p->name }}
                    {{-- <button type="submit"> --}}
                    <button type="button" data-per-id="{{ $p->id }}" class="btn-sm btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </button>
                    {{-- </form> --}}

                </li>
            @endforeach
        </ul>
    </div>
    <h3>Assign</h3>
    {{-- <form action="{{ route('role.assign.permission', $role->id) }}" method="post"> --}}
    @csrf
    <select name="permission">
        @foreach ($permissions as $p)
            <option value="{{ $p->id }}">{{ $p->name }}</option>
        @endforeach
    </select>
    <button type="button" id='buttonAssignPermission'>Send</button>
    {{-- </form> --}}
</div>

<script>
    document.getElementById('buttonAssignPermission').addEventListener('click', function() {
        assignPermissionToRol({{ $role->id }})
    })

    function setListenerToDeletePermission() {
        document.querySelectorAll('#permissionListRol button').forEach(b => {
            b.addEventListener('click', function() {
                let perId = b.getAttribute('data-per-id')

                axios.post('{{ route('role.delete.permission', $role->id) }}', {
                    'permission': perId
                }).then((res) => {
                    document.querySelector('.per_' + perId).remove()
                })
            })
        });
    }

    setListenerToDeletePermission()

    function assignPermissionToRol(roleId) {

        const perId = document.querySelector('select[name="permission"]').value
        if (document.querySelector('.per_' + perId) !== null) {
            return alert('Permission already assigned')
        }

        axios.post('{{ route('role.assign.permission', $role->id) }}', {
            'permission': perId
        }).then((res) => {
            document.querySelector('#permissionListRol').innerHTML += ` 
                    <li class="per_${ res.data.id }">
                        ${res.data.name}
                        <button type="button" data-per-id="${ res.data.id }">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
                    </li>
                `

            setListenerToDeletePermission()
        })
    }
</script>
