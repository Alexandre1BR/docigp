<div>

    <div class="card card-default">
        <div class="card-header">
            <div class="row">
                <div class="col-md-3">
                    <h4 class="mb-0">Fornecedores / Favorecidos</h4>
                </div>


                <div class="col-md-9">
                    <form action="{{ route('providers.index') }}" id="searchForm">
                        {{ csrf_field() }}

                        <div class="form-group pull-right">
                            <div class="row d-flex justify-content-end">
                                <div class="col-xs-4">

                                        <a  id="novo" href="{{ route('providers.create') }}" class="btn btn-danger pull-right mr-1">
                                            <i class="fa fa-plus"></i> Novo
                                        </a>

                                </div>

                                <div class="col-xs-8 d-flex justify-content-end">
                                    <div class="input-group">
                                        <input  dusk="search-input" class="form-control" name="search" wire:model.debounce.500ms="searchString" placeholder="Pesquisar" value="{{ $search ?? '' }}">




                                    </div>

                                </div>
                            </div>
                        </div>






                        <div class="row d-flex justify-content-end">
                            <div class="m-0 inline">
                                <input type="checkbox" name="query[filter][checkboxes][blocked_checkbox]" wire:model="isBlocked" {{(isset($query['filter']['checkboxes']['blocked_checkbox']) ? 'checked' : '') }} class="form-check-input" />
                                <label class="form-check-label d-inline-block">
                                    Apenas os bloqueados pela DOCIGP
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
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

                <table id="providersTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>CPF_CNPJ</th>
                        <th>Tipo de Pessoa</th>
                        <th>Nome</th>
                        <th>Bloqueado pela DOCIGP</th>
                    </tr>
                    </thead>

                    @forelse ($providers as $provider)
                        <tr>
                            <td>
                                <a href="{{ route('providers.show', ['id' => $provider->id]) }}">{{ $provider->cpf_cnpj }}</a>
                            </td>
                            <td>
                                {{ $provider->type }}
                            </td>
                            <td>
                                {{ $provider->name }}
                            </td>
                            @if ($provider->is_blocked)
                                <td style=color:red>
                                    <strong>
                                        Sim
                                    </strong>
                                </td>
                            @else
                                <td>
                                    Não
                                </td>
                            @endif
                        </tr>


                    @empty
                        <p>Nenhum Fornecedor ou Favorecido encontrado</p>
                    @endforelse

                    {{ $providers->links() }}
                </table>
        </div>
    </div>



</div>
