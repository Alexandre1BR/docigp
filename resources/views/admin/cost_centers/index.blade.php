@extends('layouts.app')

@section('content')
    <div class="card card-default">
        <div class="card-header">
            <div class="row">
                <div class="col-md-3">
                    <h4 class="mb-0">Centros de Custo</h4>
                </div>

                <div class="col-md-9">
                    <form action="{{ route('cost-centers.index') }}" id="searchForm">
                        @include(
                            'layouts.partials.search-form',
                            [
                                'routeSearch' => 'cost-centers.index',
                                'routeCreate' => 'cost-centers.create',
                            ]
                        )
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

            @include('admin.cost_centers.partials.table')
        </div>
    </div>
@endsection

