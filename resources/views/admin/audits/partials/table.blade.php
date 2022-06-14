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

<table id="auditsTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Data da ação</th>
            <th>Usuário</th>
            <th>Ação</th>
            <th>Entidade modificada</th>
            <th>Referência</th>
            <th>Valores antigos</th>
            <th>Valores novos</th>
            <th>Url/Rota</th>

        </tr>
    </thead>

    @forelse ($audits as $audit)
        <tr>
            <td>
                {{ $audit->formatted_created_at }}
            </td>
            <td>
                {{ $audit->user->name ?? '' }}
                <br/>
                {{ $audit->user->email ?? '' }}
            </td>
            <td>
                {{ $audit->activity ?? ''}}
            </td>
            <td>
                {{ __($audit->entity) ?? '' }}
                <br/>
                (id: {{$audit->auditable_id ?? ''}})
            </td>

            <td>

{{--                Lista--}}
{{--                <ul>--}}
{{--                @forEach($audit->reference as $item)--}}
{{--                        <li>--}}
{{--                            {{$item['field'] ?? ''}} - --}}
{{--                            {{$item['value'] ?? 'null'}}--}}
{{--                        </li>--}}
{{--                @endForEach--}}
{{--                </ul>--}}

                <table>
{{--                    <thead>--}}
{{--                    <tr>--}}
{{--                        <th>Referência</th>--}}
{{--                        <th>Valor atual</th>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}

                    @forEach($audit->reference as $item)
                        <tr>

                            <td>
                                {{$item['field'] ?? ''}}
                            </td>
                            <td>
                                {{$item['value'] ?? 'null'}}
                            </td>

                        </tr>
                    @endForEach
                </table>



            </td>

            <td>
{{--                <table>--}}
{{--                    --}}{{--                    <thead>--}}
{{--                    --}}{{--                    <tr>--}}
{{--                    --}}{{--                        <th>Referência</th>--}}
{{--                    --}}{{--                        <th>Valor atual</th>--}}
{{--                    --}}{{--                    </tr>--}}
{{--                    --}}{{--                    </thead>--}}

{{--                    @forEach($audit->old_values as $key => $value)--}}
{{--                        <tr>--}}

{{--                            <td>--}}
{{--                                {{$item['field'] ?? ''}}--}}
{{--                            </td>--}}
{{--                            <td>--}}
{{--                                {{$item['value'] ?? 'null'}}--}}
{{--                            </td>--}}

{{--                        </tr>--}}
{{--                    @endForEach--}}
{{--                </table>--}}



                {{--                {{ $audit->old_values ?? '' }}--}}
                <ul class="list-group list-group-flush">
                    @forEach($audit->old_values as $key => $value)
                        <li class="list-group-item">
                            {{__($key)}} => {{$value ?? 'null'}}
                        </li>
                    @endForEach
                </ul>
            </td>
            <td>

                <ul class="list-group list-group-flush">

                {{--                {{ $audit->new_values ?? '' }}--}}
                @forEach($audit->new_values as $key => $value)
                    <li class="list-group-item">
                        {{__($key)}} => {{$value ?? 'null'}}
                    </li>
                @endForEach

                </ul>
            </td>

            <td>
                {{ $audit->url ?? ''}}
                <br/>
                {{ $audit->route_name ?? ''}}
            </td>
        </tr>


    @empty
        <p>Nenhum dado encontrado</p>
    @endforelse

    {{ $audits->links() }}


</table>
<script>
    import Table from "../../../../js/components/app/Table";
    export default {
        components: {Table}
    }
</script>
