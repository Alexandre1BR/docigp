@extends('layouts.app')

@section('content')
<div class="card card-default">
    <div >
        <div class="card-header text-center">
            <h1>Relatórios anuais</h1>
        </div>
        <div class="card-group">
            <div class="card">
                <div class="card-body text-center" >
                    <h5 class="card-title">Relatório por Deputado</h5>
                    <form action="{{route('annual-reports.generate')}}" method="post">
                        @csrf
                        <div class="form-row justify-content-center">
                            <div class="form-group col-md-2">
                                <label for="year">Selecione o ano </label>
                                <input class='form-control' type="number" name="year" id="" min='2019' step='1'>
                            </div>
                        </div>
                        <div class="form-row justify-content-center">
                            <div class="form-group col-md-3">
                                <label for="congressman_id">Selecione o(a) deputado(a) </label>
                                <select class="custom-select" name="congressman_id" id="congressman_id">
                                    <option value="">Selecione</option>
                                    @foreach($congressmen as $congressman)
                                        <option value="{{$congressman->id}}">{{$congressman->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <button type="submit" class='btn btn-primary btn-sm'>Baixar pdf</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body text-center" >
                    <h5 class="card-title">Relatório Geral</h5>
                    <form action="{{route('general-annual-reports.generate')}}" method="post">
                        @csrf
                        <div class="form-row justify-content-center">
                            <div class="form-group col-md-2">
                                <label for="year">Selecione o ano </label>
                                <input class='form-control' type="number" name="year" id="" min='2019' step='1'>
                            </div>
                        </div>
                        <button type="submit" class='btn btn-primary btn-sm'>Baixar pdf</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
