{{ csrf_field() }}

<div class="form-group pull-right">
    <div class="row d-flex justify-content-end">
        <div class="col-xs-4">
            @if (isset($routeCreate))
                <a  id="novo" href="{{ route($routeCreate) }}" class="btn btn-danger pull-right mr-1">
                    <i class="fa fa-plus"></i> Novo
                </a>
            @endif
        </div>

        <div class="col-xs-8 d-flex justify-content-end">
            <div class="input-group">
                <input  dusk="search-input" class="form-control" name="search" wire:model="searchString" placeholder="Pesquisar" value="{{ $search ?? '' }}">

                <div class="input-group-append">
                    <span
                        class="input-group-text"
                        onClick="javascript:document.getElementById('searchForm').submit();"
                    >
                        <i dusk="search-button" class="fa fa-search"></i>
                    </span>
                </div>

                @if(isset($routeSearch))
                    <div class="input-group-append">
                        <span
                            class="input-group-text"
                            onClick="javascript:document.getElementById('searchForm').submit();"
                        >
                            <a href="{{ route($routeSearch) }}">
                                <i class="fas fa-eraser"></i> Limpar
                            </a>
                        </span>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
