<div id="vue-basic">
        <div class="card card-default">
            <form name="formulario" id="formulario" @if($provider->id) action="{{ route('providers.update', ['id' => $provider->id]) }}" @else action="{{ route('providers.store')}}" @endIf method="POST">
                {{ csrf_field() }}

                <input name="id" type="hidden" value="{{$provider->id}}" id="id">

                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-8 align-self-center">
                            <h4 class="mb-0">
                                <a href="{{ route('providers.index') }}">Favorecidos</a>

                                @if(is_null($provider->id))
                                    > NOVA
                                @else
                                    > {{ $provider->cpf_cnpj }} - {{ $provider->name }}
                                @endif
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

                    <div class="row">
                        <div class="form-group col-md-6">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="cpf_cnpj">CPF / CNPJ</label>
                                    <input
                                        class="form-control"
                                        name="cpf_cnpj"
                                        id="cpf_cnpj"
                                        v-mask="['###.###.###-##', '##.###.###/####-##']"
                                        value="{{is_null(old('cpf_cnpj')) ? $provider->cpf_cnpj : old('cpf_cnpj')}}"  @include('livewire.partials.disabled', ['model'=>$provider])
                                    />
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

                                <div class="form-group">
                                    <input class="form-control" type="hidden" name="is_blocked" value="false">
                                    <input type="checkbox" name="is_blocked" id="is_blocked" {{ $provider->is_blocked == 1 ? 'checked="checked"' : '' }}  @include('livewire.partials.disabled', ['model'=>$provider])/>
                                    <label for="is_blocked">Bloqueado pela DOCIGP</label>
                                </div>

                                <hr class="mt-2 mb-3"/>

                                <h4>Endereço</h4>
                                <div class="form-group">
                                    <label for="zipcode">CEP</label>
                                    <input
                                        class="form-control"
                                        name="zipcode"
                                        id="zipcode"
                                        v-mask="['#####-###']"
                                        wire:model.debounce.500ms="zipcode"
                                        value="{{is_null(old('zipcode')) ? $provider->zipcode : old('zipcode')}}"  @include('livewire.partials.disabled', ['model'=>$provider])
                                    />
                                </div>


                                <div class="form-group">
                                    <label for="street">Rua</label>
                                    <input
                                        class="form-control"
                                        name="street"
                                        id="street"
                                        wire:model.defer="street"
                                        value="{{is_null(old('street')) ? $provider->street : old('street')}}"  @include('livewire.partials.disabled', ['model'=>$provider])
                                    />
                                </div>

                                <div class="form-group">
                                    <label for="number">Número</label>
                                    <input
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



{{--                    @if($entries && $entries->count() > 0)--}}
{{--                    <div class="row">--}}
{{--                        <div class="form-group col-md-8">--}}
{{--                            <table id="providersTable" class="table table-striped table-bordered" cellspacing="0" width="100%">--}}
{{--                                <tr>--}}
{{--                                    <th>--}}
{{--                                        Deputado--}}
{{--                                    </th>--}}
{{--                                    <th>--}}
{{--                                        Mês / Ano do orçamento--}}
{{--                                    </th>--}}
{{--                                    <th>--}}
{{--                                        Data do Lançamento--}}
{{--                                    </th>--}}
{{--                                    <th>--}}
{{--                                        Objeto--}}
{{--                                    </th>--}}
{{--                                    <th>--}}
{{--                                        Meio--}}
{{--                                    </th>--}}
{{--                                    <th>--}}
{{--                                        Documento--}}
{{--                                    </th>--}}
{{--                                    <th>--}}
{{--                                        Centro de Custo--}}
{{--                                    </th>--}}
{{--                                    <th>--}}
{{--                                        Valor--}}
{{--                                    </th>--}}
{{--                                </tr>--}}
{{--                                @foreach($entries as $entry)--}}
{{--                                    <tr>--}}
{{--                                        <td>--}}
{{--                                            {{$entry->congressman->nickname}}--}}
{{--                                        </td>--}}
{{--                                        <td>--}}
{{--                                            {{$entry->congressmanBudget->budget->date->year}} /--}}
{{--                                            {{$entry->congressmanBudget->budget->date->month}}--}}
{{--                                        </td>--}}
{{--                                        <td>--}}
{{--                                            {{$entry->date}}--}}
{{--                                        </td>--}}
{{--                                        <td>--}}
{{--                                            {{$entry->object}}--}}
{{--                                        </td>--}}
{{--                                        <td>--}}
{{--                                            {{$entry->entryType->name}}--}}
{{--                                        </td>--}}
{{--                                        <td>--}}
{{--                                            {{$entry->document_number}}--}}
{{--                                        </td>--}}
{{--                                        <td>--}}
{{--                                            {{$entry->costCenter->name}}--}}
{{--                                        </td>--}}
{{--                                        <td>--}}
{{--                                            R$ {{$entry->value}}--}}
{{--                                        </td>--}}
{{--                                    </tr>--}}
{{--                                @endforeach--}}
{{--                            </table>--}}
{{--                            {{$entries->links()}}--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                @endif--}}
            </form>
        </div>
</div>
