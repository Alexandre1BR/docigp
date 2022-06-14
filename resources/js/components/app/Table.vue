<template>
    <div>






        <div class="d-lg-none">

            <div class="card card-parlamentar mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="">
                                <span class="text-danger"><i class="fa fa-dot-circle"></i></span> Chiquinho da Mangueira</h5>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-0">
                    <div class="row">

                        <div class="col-4 text-center d-flex align-items-center">
                            <span class="badge text-uppercase badge-success">com mandato</span>
                        </div>
                        <div class="col-8 text-center">
                            <span class="label-flags">Aderiu </span>
                            <span class="badge text-uppercase badge-success ml-1">sim</span>
                            <span class="label-flags ml-3">Pendências </span>
                            <span class="badge text-uppercase badge-success ml-1">não</span>
                        </div>
                    </div>
                </div>
            </div>




            <div class="card card-parlamentar mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="">
                                <span class="text-danger"><i class="fa fa-dot-circle"></i></span> Jorge Felippe Neto</h5>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-0">
                    <div class="row">

                        <div class="col-4 text-center d-flex align-items-center">
                            <span class="badge text-uppercase badge-success">com mandato</span>
                        </div>
                        <div class="col-8 text-center">
                            <span class="label-flags">Aderiu </span>
                            <span class="badge text-uppercase badge-success ml-1">sim</span>
                            <span class="label-flags ml-3">Pendências </span>
                            <span class="badge text-uppercase badge-danger ml-1">sim</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-parlamentar mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="">
                                <span class="text-danger"><i class="fa fa-dot-circle"></i></span> Marcelo Cabeleireiro</h5>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-0">
                    <div class="row">

                        <div class="col-4 text-center d-flex align-items-center">
                            <span class="badge text-uppercase badge-success">com mandato</span>
                        </div>
                        <div class="col-8 text-center">
                            <span class="label-flags">Aderiu </span>
                            <span class="badge text-uppercase badge-success ml-1">sim</span>
                            <span class="label-flags ml-3">Pendências </span>
                            <span class="badge text-uppercase badge-danger ml-1">sim</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-parlamentar mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="">
                                <span class="text-danger"><i class="fa fa-dot-circle"></i></span> Enfermeira Rejane</h5>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-0">
                    <div class="row">

                        <div class="col-4 text-center d-flex align-items-center">
                            <span class="badge text-uppercase badge-success">com mandato</span>
                        </div>
                        <div class="col-8 text-center">
                            <span class="label-flags">Aderiu </span>
                            <span class="badge text-uppercase badge-success ml-1">sim</span>
                            <span class="label-flags ml-3">Pendências </span>
                            <span class="badge text-uppercase badge-success ml-1">não</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-parlamentar mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="">
                                <span class="text-danger"><i class="fa fa-dot-circle"></i></span> Delegado Carlos Augusto</h5>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-0">
                    <div class="row">

                        <div class="col-4 text-center d-flex align-items-center">
                            <span class="badge text-uppercase badge-success">com mandato</span>
                        </div>
                        <div class="col-8 text-center">
                            <span class="label-flags">Aderiu </span>
                            <span class="badge text-uppercase badge-success ml-1">sim</span>
                            <span class="label-flags ml-3">Pendências </span>
                            <span class="badge text-uppercase badge-success ml-1">não</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>




        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">

                <thead>
                    <tr>
                        <slot name="thead"></slot>
                        <th
                            v-if="columns"
                            v-for="column in columns"
                            :class="
                                isObject(column)
                                    ? column.title === 'Status'
                                        ? 'col-' + statusSize + ' ' + column.trClass
                                        : column.title === 'Ações'
                                        ? 'col-' + actionsSize + ' ' + column.trClass
                                        : column.trClass
                                    : ''
                            "
                        >
                            <span v-if="!isObject(column)">
                                {{ column }}
                            </span>

                            <span v-if="isComponent(column, 'label')" v-html="column.title"> </span>

                            <span v-if="column.title === 'Solicitado'">
                                <b-button
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    title="Valor de referencia R$ 26.819,98"
                                    class="btn btn-micro"
                                    ><svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="12"
                                        height="12"
                                        fill="currentColor"
                                        class="bi bi-info-circle"
                                        viewBox="0 0 16 16"
                                    >
                                        <path
                                            d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"
                                        ></path>
                                        <path
                                            d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"
                                        ></path></svg
                                ></b-button>
                            </span>

                            <span v-if="isComponent(column, 'checkbox')">
                                <input
                                    @change="$emit('input-checkbox-' + column.id, $event)"
                                    type="checkbox"
                                    :id="column.id"
                                />
                            </span>
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <slot></slot>
                </tbody>
            </table>
        </div>

        <app-pagination
            v-if="pagination"
            :pagination="pagination"
            @goto-page="$emit('goto-page', $event)"
        ></app-pagination>
    </div>
</template>

<script>
export default {
    props: ['pagination', 'columns', 'rows', 'statusSize', 'actionsSize'],

    methods: {
        isObject(target) {
            return is_object(target)
        },

        isComponent(component, componentName) {
            return component.hasOwnProperty('type') && component.type == componentName
        },
    },
}
</script>
