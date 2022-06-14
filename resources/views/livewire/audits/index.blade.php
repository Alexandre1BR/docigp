<div>
    <div class="card card-default">
        <div class="card-header">
            <div class="row">
                <div class=" form-group col-md-4">
                    <h4 class="mb-0">Logs de atividades</h4>
                </div>



                <div class="col-md-8">
                    <form>
                        <div class="form-row justify-content-end">
                            <div class="form-group col-lg-3 col-md-4" wire:ignore>
                                <label class="col-form-label" for="user_id">Usuário:</label>
                                <select data-allow-clear="true" id="user_id" name="user_id" class="select2" data-placeholder="Selecione um usuário">
                                    <option value=""></option>
                                    @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endForEach
                                </select>
                            </div>


                            {{-- <div class="input-group">--}}
                            {{-- <input dusk="search-input" class="form-control" name="search" wire:model.debounce.500ms="searchString" placeholder="Pesquisar">--}}
                            {{-- </div>--}}


                            <div class="form-group col-lg-3 col-md-4">
                                <label for="created_at_start" class="col-form-label">Data de:</label>
                                <input id="created_at_start" wire:model="created_at_start" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="created_at_start" value="{{$created_at_start ?? old("created_at_start")}}" type="date">
                            </div>
                            <div class="form-group col-lg-3 col-md-4">
                                <label for="created_at_end" class="col-form-label"> Até: </label>
                                <input id="created_at_end" wire:model="created_at_end" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="created_at_end" value="{{$created_at_end ?? old("created_at_end")}}" type="date">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body">
            <table id="auditsTable" class="table table-striped table-bordered">
                <thead>
                    <tr class="text-center">
                        <th class="align-middle">Data da ação</th>
                        <th class="align-middle">Usuário</th>
                        <th class="align-middle">Ação</th>
                        <th class="align-middle">Entidade modificada</th>
                        <th class="align-middle">Referência</th>
                        <th class="align-middle">Valores antigos</th>
                        <th class="align-middle">Valores novos</th>
                        <th class="align-middle">Url/Rota</th>

                    </tr>
                </thead>

                @forelse ($audits as $audit)
                <tr>
                    <td>
                        {{ $audit->formatted_created_at }}
                    </td>
                    <td>
                        {{ $audit->user->name ?? '' }}
                        <br />
                        {{ $audit->user->email ?? '' }}
                    </td>
                    <td>
                        {{ $audit->activity ?? ''}}
                    </td>
                    <td>
                        {{ __($audit->entity) ?? '' }}
                        <br />
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
                        <br />
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
