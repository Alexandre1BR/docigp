<div>

        <div class="card card-default">
            <form name="formulario" id="formulario"
                  action="{{ $mode == 'update' ? route('providers.update', ['id' => $provider->id]) : route('providers.store')}}"
                  method="POST">
                {{ csrf_field() }}

                <div
                    x-init="VMasker($refs.cpfcnpj).maskPattern(cpfcnpjmask[0]);
                    $refs.cpfcnpj.addEventListener('input', inputHandler.bind(undefined, cpfcnpjmask, 14), false);
                    VMasker($refs.zipcode).maskPattern(cepmask);"
                    x-data="{ isEditing: {{$mode == 'create' ? 'true' : 'false'}}, cepmask: '99999-999', cpfcnpjmask: ['999.999.999-999', '99.999.999/9999-99']}"
                    @focus-field.window="$refs[$event.detail.field].focus()"
                >

                <input name="id" type="hidden" value="{{$provider->id}}" id="id">

                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-8 align-self-center">
                            <h4 class="mb-0">
                                <a href="{{ route('providers.index') }}">Favorecidos</a>

                                {{ is_null($provider->id) ? ('> NOVA') : ('> '.$provider->cpf_cnpj.' - '.$provider->name) }}
                            </h4>
                        </div>

                        <div class="col-sm-4 align-self-center d-flex justify-content-end">
                            @include('livewire.partials.edit-button', ['model'=>$provider])
                            @include('livewire.partials.save-button', ['model'=>$provider, 'backUrl' => 'providers.index'])
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @include('partials.alerts')
                    @if ($errors->has('cpf_cnpj'))
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first('cpf_cnpj') }}
                        </div>
                    @endif
                    @if ($errors->has('type'))
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first('type') }}
                        </div>
                    @endif
                    @if ($errors->has('name'))
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                </div>



                    <div class="row">
                        <div class="form-group col-md-6">
                            <div class="col-md-12">

                                <div id="vue-basico">

                                <div class="form-group"

                                >
                                    <label for="cpf_cnpj">CPF / CNPJ</label>
                                    <input
                                        class="form-control @error('cpfcnpj') is-invalid @endError"
                                        name="cpf_cnpj"
                                        id="cpf_cnpj"
                                        wire:model.debounce.800ms="cpfCnpj"
                                        x-ref="cpfcnpj"
                                        @include('livewire.partials.disabled', ['model'=>$provider])
                                    />

                                    <div>
                                        @error('cpfcnpj')
                                            <small class="text-danger">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                {{ $message }}
                                            </small>
                                        @endError
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="type">Tipo Pessoa</label>
                                    <select class="custom-select" name="type" id="type"  @include('livewire.partials.disabled', ['model'=>$provider])>
                                        <option value="">Selecione</option>
                                        <option value="PF" {{(is_null(old('type')) ? $provider->type : old('type')) == 'PF' ? 'selected=selected' : ''}}>Pessoa Física</option>
                                        <option value="PJ" {{(is_null(old('type')) ? $provider->type : old('type')) == 'PJ' ? 'selected=selected' : ''}}>Pessoa Jurídica</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="name">Nome</label>
                                    <input class="form-control" name="name" id="name" value="{{is_null(old('name')) ? $provider->name : old('name')}}"  @include('livewire.partials.disabled', ['model'=>$provider])/>
                                </div>

                                <hr class="mt-2 mb-3"/>

                                <h4>Endereço</h4>
                                <div class="form-group"

                                >
                                    <label for="zipcode">CEP</label>
                                    <input

                                        x-ref="zipcode"
                                        class="form-control @error('zipcode') is-invalid @endError"
                                        name="zipcode"
                                        id="zipcode"
                                        wire:model.debounce.800ms="zipcode"
                                        @include('livewire.partials.disabled', ['model'=>$provider])
                                    />

                                    <div>
                                        @error('zipcode')
                                            <small class="text-danger">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                {{ $message }}
                                            </small>
                                        @endError
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="street">Rua</label>
                                    <input
                                        class="form-control"
                                        name="street"
                                        id="street"
                                        wire:model.defer="street"
                                        value="{{is_null(old('street')) ? $provider->street : old('street')}}"
                                        @include('livewire.partials.disabled', ['model'=>$provider])
                                    />
                                </div>

                                <div class="form-group">
                                    <label for="number">Número</label>
                                    <input
                                        x-ref="number"
                                        class="form-control"
                                        name="number"
                                        id="number"
                                        wire:model.defer="number"
                                        value="{{is_null(old('number')) ? $provider->number : old('number')}}"  @include('livewire.partials.disabled', ['model'=>$provider])
                                    />
                                </div>

                                <div class="form-group">
                                    <label for="complement">Complemento</label>
                                    <input
                                        class="form-control"
                                        name="complement"
                                        id="complement"
                                        wire:model.defer="complement"
                                        value="{{is_null(old('complement')) ? $provider->complement : old('complement')}}"  @include('livewire.partials.disabled', ['model'=>$provider])
                                    />
                                </div>

                                <div class="form-group">
                                    <label for="neighborhood">Bairro</label>
                                    <input
                                        class="form-control"
                                        name="neighborhood"
                                        id="neighborhood"
                                        wire:model.defer="neighborhood"
                                        value="{{is_null(old('neighborhood')) ? $provider->neighborhood : old('neighborhood')}}"  @include('livewire.partials.disabled', ['model'=>$provider])
                                    />
                                </div>

                                <div class="form-group">
                                    <label for="city">Cidade</label>
                                    <input
                                        class="form-control"
                                        name="city"
                                        id="city"
                                        wire:model.defer="city"
                                        value="{{is_null(old('city')) ? $provider->city : old('city')}}"  @include('livewire.partials.disabled', ['model'=>$provider])
                                    />
                                </div>

                                <div class="form-group">
                                    <label for="state">Estado</label>
                                    <input
                                        class="form-control"
                                        name="state"
                                        id="state"
                                        wire:model.defer="state"
                                        value="{{is_null(old('state')) ? $provider->state : old('state')}}"  @include('livewire.partials.disabled', ['model'=>$provider])
                                    />
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div>

                    @if(isset($entries) && $entries && $entries->count() > 0)
                        <div class="row">
                            <div class="form-group col-md-8">
                                <table id="providersTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <tr>
                                        <th>
                                            Deputado
                                        </th>
                                        <th>
                                            Mês / Ano do orçamento
                                        </th>
                                        <th>
                                            Data do Lançamento
                                        </th>
                                        <th>
                                            Objeto
                                        </th>
                                        <th>
                                            Meio
                                        </th>
                                        <th>
                                            Documento
                                        </th>
                                        <th>
                                            Centro de Custo
                                        </th>
                                        <th>
                                            Valor
                                        </th>
                                    </tr>
                                    @foreach($entries as $entry)
                                        <tr>
                                            <td>
                                                {{$entry->congressman->nickname}}
                                            </td>
                                            <td>
                                                {{$entry->congressmanBudget->budget->date->year}} /
                                                {{$entry->congressmanBudget->budget->date->month}}
                                            </td>
                                            <td>
                                                {{$entry->date}}
                                            </td>
                                            <td>
                                                {{$entry->object}}
                                            </td>
                                            <td>
                                                {{$entry->entryType->name}}
                                            </td>
                                            <td>
                                                {{$entry->document_number}}
                                            </td>
                                            <td>
                                                {{$entry->costCenter->name}}
                                            </td>
                                            <td>
                                                R$ {{$entry->value}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                                {{$entries->links()}}

                            </div>
                        </div>
                    @endif




                </div>






                <div class="col-md-6">
                <div class="row">
                    <div class="col-md-10">
                        <h4 class="mb-0">
                            Períodos de bloqueio
                        </h4>
                    </div>

                    <div class="col-md-2 text-right">
                        <button type="button" wire:click="clearPeriod" class="btn btn-primary" data-toggle="modal" data-target="#period-modal">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                <!-- Modal -->
                <div wire:ignore.self class="modal fade" id="period-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">{{$this->selectedId ? 'Alterar' : 'Criar'}} período</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true close-btn">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>

                                    <input type="hidden" class="form-control" name="provider_id" id="provider_id" wire:model.defer="provider_id">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Início</label>
                                        <input type="date" class="form-control" id="start_date" wire:model.defer="start_date">


                                        <div>
                                            @error('start_date')
                                                <small class="text-danger">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    {{ $message }}
                                                </small>
                                            @endError
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlInput2">Fim</label>
                                        <input type="date" class="form-control" id="end_date" wire:model.defer="end_date" >

                                        <div>
                                            @error('end_date')
                                                <small class="text-danger">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    {{ $message }}
                                                </small>
                                            @endError
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">


                                <button type="button" wire:click.prevent="clearPeriod" class="btn btn-outline-danger btn-sm close-btn" data-dismiss="modal">Cancelar</button>
                                <button type="button" wire:click.prevent="store()" class="btn btn-success btn-sm close-modal">Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    @if(isset($this->provider->blockedPeriods) && $this->provider->blockedPeriods && $this->provider->blockedPeriods->count() > 0)
                        <div class="row">
                            <div class="form-group col-md-12">
                                <table id="providersTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <tr>
                                        <th>
                                            Início
                                        </th>
                                        <th>
                                            Fim
                                        </th>
                                        <th>

                                        </th>
                                    </tr>

                                    @foreach($this->provider->blockedPeriods()->orderBy('start_date')->get() as $period)
                                        <tr>
                                            <td>
                                                {{date('d/m/Y', strtotime($period->start_date))}}
                                            </td>
                                            {{--                                            https://talltips.novate.co.uk/livewire/sweetalert2-with-livewire--}}
                                            <td>
                                                {{$period->end_date ? date('d/m/Y', strtotime($period->end_date)) : ''}}
                                            </td>
                                            <td>
                                                <button
                                                    type="button"
                                                    wire:click="prepareForUpdate({{$period->id}})"
                                                    class="btn btn-sm  btn-primary"
                                                >
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button type="button" wire:click="prepareForDelete({{ $period->id }})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>




                                            </td>
                                        </tr>
                                    @endforeach
                                </table>

                            </div>
                        </div>
                    @endif
                </div>
                </div>











            </form>


        </div>





</div>


