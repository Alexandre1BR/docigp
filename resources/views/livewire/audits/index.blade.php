

<div>
    <div class="card card-default">
        <div class="card-header">
            <div class="row">
                <div class="col-md-3">
                    <h4 class="mb-0">Logs de atividades</h4>
                </div>



                <div class="col-md-9">
                        <div class="form-group pull-right">
                            <div class="row d-flex justify-content-end">
                                <div class="col-xs-12 d-flex justify-content-end">
                                    <label class="form-check-label d-inline-block" for="user_id">Usuário</label>
                                    <div class="input-group">
                                        <div wire:ignore>
                                            <select data-allow-clear="true"
                                                    id="user_id"
                                                    name="user_id"
                                                    class="select2" data-placeholder="Selecione um usuário">
                                                <option value=""></option>
                                                @foreach($users as $user)
                                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                                @endForEach

                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>


                </div>
            </div>
        </div>

        <div class="card-body">
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
                            <table>
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
        </div>
    </div>



</div>