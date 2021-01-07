@extends('layouts.app')

@section('content')
    <div class="card card-default">
        <div class="card-header">
            <div class="row">
                <div class="col-md-3">
                    <h4 class="mb-0">Fornecedores / Favorecidos</h4>
                </div>


                <div class="col-md-9">
                    @include(
                        'layouts.partials.search-form',
                        [
                            'routeSearch' => 'providers.index',
                            'routeCreate' => 'providers.create',
                            'customFields' => '<div class="justify-content-end" style="white-space: nowrap;">
                    <div class="m-0 inline">
                        <input type="checkbox" name="query[filter][checkboxes][blocked_checkbox]" '.(isset($query['filter']['checkboxes']['blocked_checkbox']) ? 'checked' : '') .' class="form-check-input" />
                        <label class="form-check-label d-inline-block">
                            Apenas os bloqueados pela DOCIGP
                        </label>
                </div>
            </div>',
                        ]
                    )
                </div>
            </div>
        </div>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @include('admin.providers.partials.table')
        </div>
    </div>
@endsection

