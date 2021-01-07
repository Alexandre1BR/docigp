@extends('layouts.pdf')
@section('title')
    <h1>
        PRESTAÇÃO DE CONTAS ANUAL DOCIGP – EXERCÍCIO {{$year}}<br/>
        Relatório após verificação e aprovação pela Subdiretoria-Geral de Controle Interno
    </h1>
@endsection
@section('content')
    @include('admin.annual-reports.partials.table')
@endsection
