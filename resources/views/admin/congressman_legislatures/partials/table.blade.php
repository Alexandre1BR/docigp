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

<table id="congressmanLegislatures" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Deputado</th>
            <th>Legislatura</th>
            <th>Data início</th>
            <th>Data Fim</th>
            <th>Ações</th>

        </tr>
    </thead>

    <tbody>

        @forelse ($congressmanLegislatures as $congressmanLegislature)
            <tr>
                <td>
                    {{$congressmanLegislature->id}}
                </td>

                <td>
                   <a href="{{ route('congressmen.show', ['id' => $congressmanLegislature['id']])}}"> {{$congressmanLegislature->congressman->name}} ({{$congressmanLegislature->congressman->party->code}})</a>
                </td>

                <td>
                    <a href="{{ route('legislatures.show', ['id' => $congressmanLegislature->legislature->id])}}">{{ $congressmanLegislature->legislature->number}}</a>
                </td>

                <td>
                    {{ $congressmanLegislature->started_at ? date('d/m/Y', strtotime($congressmanLegislature->started_at)) : ''}}
                </td>

                <td>
                    {{ $congressmanLegislature->ended_at ? date('d/m/Y', strtotime($congressmanLegislature->ended_at)) : ''}}
                </td>
                <td>
                    <a id="button-novo-contato" href="#" data-toggle="modal" data-target="#editCongressmanFromLegislatures_{{$congressmanLegislature->id}}"
                       class="btn btn-success btn-sm pull-right">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    @include('admin.congressman_legislatures.partials.form-modal',[$congressmanLegislature,$congressman])
                </td>
            </tr>
        @empty
            <p>Nenhum Deputado encontrado</p>
        @endforelse
    </tbody>
</table>
{{$congressmanLegislatures->links()}}


