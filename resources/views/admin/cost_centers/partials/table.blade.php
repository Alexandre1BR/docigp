@if(session()->has('message'))
<div class="alert alert-success">
    {{ session()->get('message') }}
</div>
@endif

@if(session()->has('warning'))
<div class="alert alert-warning">
    {{ session()->get('warning') }}
</div>
@endif

<div class="table-responsive">
    <table id="costCentersTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th width="50%">Nome</th>
                <th>Código</th>
                <th>Filho de</th>
                <th>Frequência</th>
                <th class="text-right">Limite</th>
                <th class="text-center">Acumulável</th>
                <th class="text-center">Revogado em</th>
            </tr>
        </thead>


        @forelse ($costCenters as $costCenter)
        <tr>
            <td width="60%">
                <a href="{{ route('cost-centers.show', ['id' => $costCenter['id']]) }}">{{ $costCenter['name'] }}</a>
            </td>
            <td>
                {{ $costCenter['code'] }}
            </td>
            <td>
                {{ $costCenter['parent_code'] }}
            </td>
            <td>
                {{ $costCenter['frequency'] }}
            </td>
            <td class="text-right">
                {{ $costCenter['limit'] }}
            </td>
            <td class="text-center">
                @if ($costCenter['can_accumulate'])
                <label class="badge badge-success"> Sim </label>
                @else
                <label class="badge badge-danger"> Não </label>
                @endif
            </td>
            <td class="text-center">
                {{$costCenter['formatted_revoked_at']}}
            </td>
        </tr>
        @empty
        <p>Nenhum Centro de Custo encontrado</p>
        @endforelse
    </table>
</div>
