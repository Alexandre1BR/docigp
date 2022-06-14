@extends('layouts.app')

@section('content')
<div class="card card-default" id="vue-legislatures">
    <form name="formulario" id="formulario" @if(formMode()=='show' ) action="{{ route('legislatures.update', ['id' => $legislature->id]) }}" @else action="{{ route('legislatures.store')}}" @endIf method="POST">
        {{ csrf_field() }}
        <input name="id" type="hidden" value="{{ $legislature->id }}" id="id">

        <div class="card-header">
            <div class="row">
                <div class="col-sm-8 align-self-center">
                    <h4 class="mb-0">
                        <a href="{{ route('legislatures.index') }}">Legislaturas</a>

                        @if(is_null($legislature->id))
                        > NOVA
                        @else
                        > {{ $legislature->number }}
                        @endif
                    </h4>
                </div>

                <div class="col-sm-4 align-self-center d-flex justify-content-end">
                    @include('partials.edit-button', ['model'=>$legislature])
                    @include('partials.save-button', ['model'=>$legislature, 'backUrl' => 'legislatures.index'])
                </div>
            </div>
        </div>

        <div class="card-body">
            @include('partials.alerts')
            @if ($errors->has('number'))
            <div class="alert alert-danger" role="alert">
                {{ $errors->first('number') }}
            </div>
            @endif
            @if ($errors->has('year_start'))
            <div class="alert alert-danger" role="alert">
                {{ $errors->first('year_start') }}
            </div>
            @endif
            @if ($errors->has('year_end'))
            <div class="alert alert-danger" role="alert">
                {{ $errors->first('year_end') }}
            </div>
            @endif


            <div class="form-row col-md-12 pr-2">
                <div class="form-group col-md-4 col-sm-12">
                    <label for="number">Número</label>
                    <input class="form-control" name="number" id="number" value="{{is_null(old('number')) ? $legislature->number : old('number')}}" @include('partials.disabled', ['model'=>$legislature])/>
                </div>

                <div class="form-group col-md-4 col-sm-12">
                    <label for="year_start">Ano de início</label>
                    <input class="form-control" name="year_start" id="year_start" value="{{is_null(old('year_start')) ? $legislature->year_start : old('year_start')}}" @include('partials.disabled', ['model'=>$legislature])/>
                </div>

                <div class="form-group col-md-4 col-sm-12">
                    <label for="year_end">Ano final</label>
                    <input class="form-control" name="year_end" id="year_end" value="{{is_null(old('year_end')) ? $legislature->year_end : old('year_end')}}" @include('partials.disabled', ['model'=>$legislature])/>
                </div>
            </div>


            @if (isset($congressmanLegislatures))

            <div class="form-group col-md-12 pt-2">
                @include('admin.congressman_legislatures.partials.table')
            </div>

            @endif
        </div>
    </form>
</div>
@endsection
