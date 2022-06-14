@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col text-right">
            <a data-target="#showBlockedProviders" href="#showBlockedProviders"
               data-toggle="modal" class="btn btn-danger btn-sm">
                <i class="fas fa-ban"></i> Lista de fornecedores bloqueados pela DOCIGP
            </a>
            <a href="/files/docigp.pdf" class="btn btn-primary btn-sm" download>
                <i class="fa fa-cloud-download-alt"></i> Baixar Ato Nº 641/2019
            </a>
        </div>
    </div>

    <div class="container-fluid" id="vue-entries">
        <app-account></app-account>
    </div>
    @include('admin.providers.partials.modal')
@endsection
