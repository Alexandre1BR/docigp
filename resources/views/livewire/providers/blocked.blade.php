<div class="card card-default">


    <div class="card-header">
        <div class="alert alert-danger" role="alert">
            Esta lista não esgota os impedimentos para a realização de despesas em conformidade com os termos do art. 8º, § 12, do <a href="/files/docigp.pdf" class="alert-link" download>Ato N/MD Nº 641/2019</a>.
        </div>

        <hr class="mt-2 mb-3"/>

        <div class="row">
            <div class="col-md-6">
                <h4 class="mb-0">Fornecedores bloqueados pela DOCIGP</h4>
            </div>


            <div class="col-md-6">
                <div id="searchForm">
                    {{ csrf_field() }}
                    <div class="form-group pull-right">
                        <div class="row d-flex justify-content-end">
                            <div class="col-xs-8 d-flex justify-content-end">
                                <div class="input-group">
                                    <input  dusk="search-input" class="form-control" name="search" wire:model.debounce.500ms="searchString" placeholder="Pesquisar" value="{{ $search ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <table id="providersTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>CPF/CNPJ</th>
            <th>Tipo</th>
            <th>Nome</th>
            <th>Endereço</th>
        </tr>
        </thead>

        @forelse ($providers as $provider)
            <tr>
                <td>
                    {{ $provider->cpf_cnpj }}
                </td>
                <td>
                    {{ $provider->type }}
                </td>
                <td>
                    {{ $provider->name }}
                </td>
                <td>
                    {{ $provider->full_address }}
                </td>
            </tr>
        @empty
            <p>Nenhum Fornecedor ou Favorecido encontrado</p>
        @endforelse


    </table>
    <div class="d-flex justify-content-center" style="margin: 10px">
        {{ $providers->links() }}
    </div>
</div>
