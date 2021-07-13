@if(isset($model) && ! is_null($model->id))
    <button
        class="btn btn-primary ml-1"
        type="button"
        id="edit-button"
        wire:click="edit()"
        {{ $isEditing ? 'disabled' : '' }}
    >
        <i class="fas fa-pencil-alt"></i> Alterar
    </button>
@endif
