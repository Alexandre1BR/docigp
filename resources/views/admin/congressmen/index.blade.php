@extends('layouts.app')

@section('content')
    <div class="card card-default">
        <div class="card-header">
            <div class="row">
                <div class="col-md-3 align-self-center">
                    <h4 class="mb-0">Deputados</h4>
                </div>

                <div class="col-md-9">
                    <form action="{{ route('congressmen.index') }}" id="searchForm">
                        @include(
                            'layouts.partials.search-form',
                            [
                                'routeSearch' => 'congressmen.index'
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

            @include('admin.congressmen.partials.table')
        </div>
    </div>
@endsection
