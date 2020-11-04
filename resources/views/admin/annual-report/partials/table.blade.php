<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Login</th>
            <th>Status</th>
            <th>Perfis</th>
        </tr>
    </thead>

    @forelse ($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td><a href="{{ route('users.show', ['id' => $user->id]) }}">{{ $user->name }}</a></td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->username }}</td>
            <td class="text-center">
                Habilitar
            </td>
            <td>{{ $user->roles_string }}</td>
        </tr>
    @empty
        <p>Nenhum Usuário encontrado.</p>
    @endforelse
</table>