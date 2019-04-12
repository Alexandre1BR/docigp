@extends('layouts.app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-3">

                    <h3>Usuários</h3>

                </div>
            </div>
        </div>

        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <a href="{{route('users.create')}}" id="button-new" class="btn btn-primary pull-right">Novo</a>

            @include('admin.users.partials.table')
        </div>
    </div>
@endsection
