<!-- Modal de Inclusão na Legislatura -->
<div class="modal fade" id="includeCongressmanInLegislatures"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Incluir na Legislatura</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form" id="form_insertContact" name="form_insertContact" action="{{route('congressman-legislatures.include-in-legislature')}}" method="post">
                <div class="modal-body">
                    {{ csrf_field() }}

                    <input name="congressman_id" type="hidden" value="{{ $congressman->id }}">

                    <div class="row">

                        <div class="col-md-5">
                            <p><label for="started_at">Data de início</label></p>

                            <select name="legislature_id">
                                @foreach($legislatures as $legislature)
                                    <option value="{{$legislature->id}}"> {{$legislature->number}}° Legislatura</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <p><label for="started_at">Data de início</label></p>
                            <input type="date" name="started_at" id="started_at">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button id="cancelContactButton" type="button" class="btn btn-success ml-1" data-dismiss="modal">
                        <i class="fas fa-ban"></i> Cancelar
                    </button>
                    <button id="saveContactButton" type="submit" class="btn btn-outline-danger ml-1">
                        <i class="fa fa-save"></i> Gravar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
