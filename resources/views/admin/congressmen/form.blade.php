@extends('layouts.app')

@section('content')
    <div class="card card-default">
        <form name="formulario" id="formulario" action="{{ route('congressmen.associate-with-user') }}" method="POST">
            {{ csrf_field() }}

            <input name="id" type='hidden' value="{{$congressman->id}}" id="id" >

            <div class="card-header">
                <div class="row">
                    <div class="col-sm-8 align-self-center">
                        <h4 class="mb-0">
                            <a href="{{ route('congressmen.index') }}">Deputados</a>

                            @if(is_null($congressman->id))
                                > NOVA
                            @else
                                > {{ $congressman->name }}
                            @endif
                        </h4>
                    </div>

                    <div class="col-sm-4 align-self-center d-flex justify-content-end">
                        @include('partials.edit-button', ['model' => $congressman])
                        @include('partials.save-button', ['model' => $congressman, 'backUrl' => 'congressmen.index'])
                    </div>
                </div>
            </div>

            <div class="card-body">
                @include('partials.alerts')
                @if ($errors->has('email'))
                    <div class="alert alert-danger" role="alert">
                        {{ $errors->first('email') }}
                    </div>
                @endif

                @if ($congressman->photo_url_linkable)
                    <div class="row">
                        <div class="form-group col-md-6" >
                            <img src="{{ $congressman->photo_url_linkable }}" width="250px">
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="form-group col-md-6" >
                        <label for="name">Nome</label>
                        <input name="name" value="{{is_null(old('name')) ? $congressman->name : old('name')}}" class="form-control" id="name" aria-describedby="nameHelp" placeholder="name" readonly="readonly">
                    </div>

                </div>

                <div class="row">
                    <div class="form-group col-md-6" >
                        <label for="nickname">Nome Público</label>
                        <input name="nickname" value="{{is_null(old('nickname')) ? $congressman->nickname : old('nickname')}}" class="form-control" id="nickname" aria-describedby="nicknameHelp" placeholder="nickname" readonly="readonly">
                    </div>

                </div>

                <div class="row">
                    <div class="form-group col-md-6" >
                        <label for="party">Partido</label>
                        <input name="party" value="{{is_null(old('party_name')) ? $congressman->party->name : old('party_name')}}" class="form-control" id="party" aria-describedby="nicknameHelp" placeholder="party_name" readonly="readonly">
                    </div>

                </div>

                <div class="row">
                    <div class="form-group col-md-6" >
                        <label for="email">Email:</label>

                        <input name="email" value="{{is_null(old('email')) ? is_null($congressman->user)?'':$congressman->user->email: old('email')}}" class="form-control" id="email" aria-describedby="emailHelp" placeholder="email">
                    </div>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-6" >

                    <div class="col-5 col-md-8 text-right">
                        <a id="button-novo-contato" href="#" data-toggle="modal" data-target="#includeCongressmanInLegislatures"
                           class="btn btn-primary btn-sm pull-right">
                            <i class="fa fa-plus"></i>
                            Incluir na Legislatura
                        </a>
                    </div>
                    Legislaturas

                    @include('admin.congressman_legislatures.partials.table')

                    @include('admin.congressman_legislatures.partials.form-modal-include')


                </div>
            </div>
        </div>
    </div>
@endsection
