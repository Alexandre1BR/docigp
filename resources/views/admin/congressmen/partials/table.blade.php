@if (session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif

@if (session()->has('warning'))
    <div class="alert alert-warning">
        {{ session()->get('warning') }}
    </div>
@endif

<div class="table-responsive"></div>
<table id="congressmenTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Foto</th>
            <th>Nome</th>
            <th>Nome Público</th>
            <th>Partido</th>
        </tr>
    </thead>

    <tbody>
        @forelse ($congressmen as $congressman)
            <tr>
                <td>
                    <a href="{{ route('congressmen.show', ['id' => $congressman['id']]) }}">{{$congressman['id']}}</a>
                </td>

                <td>
                    @if (isset($congressman['thumbnail_url_linkable']))
                        <a href="{{ route('congressmen.show', ['id' => $congressman['id']]) }}">
                            <img src="{{ $congressman['thumbnail_url_linkable'] }}" width="60px">
                        </a>
                    @endif
                </td>

                <td>
                    <a> {{ $congressman['name']}}</a>
                </td>

                <td>
                    {{ $congressman['nickname']}}
                </td>

                <td>
                    {{ $congressman['party']['name'] }} ({{$congressman['party']['code']}})
                </td>
            </tr>
        @empty
            <p>Nenhum Deputado encontrado</p>
        @endforelse
    </tbody>
</table>

{{ $congressmen->links() }}
