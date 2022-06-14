@extends('layouts.app')

@section('content')
<div class="card card-default" id="vue-cost_centers">
    <form name="formulario" id="formulario" @if(formMode()=='show' ) action="{{ route('cost-centers.update', ['id' => $costCenter->id]) }}" @else action="{{ route('cost-centers.store')}}" @endIf method="POST">
        {{ csrf_field() }}
        <input name="id" type="hidden" value="{{$costCenter->id}}" id="id">

        <div class="card-header">
            <div class="row">
                <div class="col-sm-7 align-self-center">
                    <h4 class="mb-0">
                        <a href="{{ route('cost-centers.index') }}">Centros de Custo</a>

                        @if(is_null($costCenter->id))
                        > NOVA
                        @else
                        > {{ $costCenter->cpf_cnpj }} - {{ $costCenter->name }}
                        @endif
                    </h4>
                </div>

                <div class="col-sm-5 align-self-center d-flex justify-content-end pt-1">
                    @include('partials.edit-button', ['model'=>$costCenter])
                    @include('partials.save-button', ['model'=>$costCenter, 'backUrl' => 'cost-centers.index'])
                </div>
            </div>
        </div>

        <div class="card-body">
            @include('partials.alerts')
            @if ($errors->has('name'))
            <div class="alert alert-danger" role="alert">
                {{ $errors->first('name') }}
            </div>
            @endif
            @if ($errors->has('code'))
            <div class="alert alert-danger" role="alert">
                {{ $errors->first('code') }}
            </div>
            @endif
            @if ($errors->has('parent_code'))
            <div class="alert alert-danger" role="alert">
                {{ $errors->first('parent_code') }}
            </div>
            @endif
            @if ($errors->has('frequency'))
            <div class="alert alert-danger" role="alert">
                {{ $errors->first('frequency') }}
            </div>
            @endif
            @if ($errors->has('limit'))
            <div class="alert alert-danger" role="alert">
                {{ $errors->first('limit') }}
            </div>
            @endif

            <div class="form-row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="name">Nome</label>
                        <input class="form-control" name="name" id="name" value="{{is_null(old('name')) ? $costCenter->name : old('name')}}" @include('partials.disabled', ['model'=>$costCenter])/>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="code">Código</label>
                            <input class="form-control" name="code" id="code" value="{{is_null(old('code')) ? $costCenter->code : old('code')}}" @include('partials.disabled', ['model'=>$costCenter])/>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="parent_code">Código Superior</label>
                            <input class="form-control" name="parent_code" id="parent_code" value="{{is_null(old('parent_code')) ? $costCenter->parent_code : old('parent_code')}}" @include('partials.disabled', ['model'=>$costCenter])/>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="frequency">Frequência</label>
                            <input class="form-control" name="frequency" id="frequency" value="{{is_null(old('frequency')) ? $costCenter->frequency : old('frequency')}}" @include('partials.disabled', ['model'=>$costCenter])/>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-5">
                            <label for="limit">Limite</label>
                            <input class="form-control" name="limit" id="limit" value="{{is_null(old('limit')) ? $costCenter->limit : old('limit')}}" @include('partials.disabled', ['model'=>$costCenter])/>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="can_accumulate">Acumulável</label>
                            <input class="form-control" type="hidden" name="can_accumulate" value="false">
                            <input class="form-control" type="checkbox" name="can_accumulate" id="can_accumulate" {{(is_null(old('can_accumulate')) ? $costCenter->can_accumulate : old('can_accumulate')) ? 'checked="checked"' : ''}} @include('partials.disabled', ['model'=>$costCenter])/>
                        </div>

                        <div class="form-group col-md-5">
                            <label for="revoked_at">Revogado em</label>
                            <input value="{{is_null(old('revoked_at')) ? $costCenter->revoked_at : old('revoked_at')}}" type="date" name="revoked_at" class="form-control" id="revoked_at" @include('partials.disabled', ['model'=>$costCenter])
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
