<template>
    <div>
        <div class="table-responsive">
        <table
            class="table table-md table-hover table-borderless table-striped card-body mb-0"
        >
            <thead>
                <tr >
                    <slot name="thead"></slot>
                    <th
                        v-if="columns"
                        v-for="column in columns"
                        :class="isObject(column) ? column.trClass : ''"
                    >

                    
                        <span v-if="!isObject(column)">
                            {{ column }}
                        </span>
                        
                        
                        <span v-if="isComponent(column, 'label')" 
                            v-html="column.title"
                    
                        >
                        </span> 

                        <span v-if="column.title==='Solicitado'" 
                            
                    
                        > <b-button data-toggle="tooltip" data-placement="top" title="Valor de referencia R$ 26.819,98" class="btn btn-micro"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"></path>
  <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"></path>
</svg></b-button>
                        
                        </span> 
                    
                        <span v-if="isComponent(column, 'checkbox')">
                            <input
                                @change="
                                    $emit('input-checkbox-' + column.id, $event)
                                "
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
    props: ['pagination', 'columns', 'rows'],

    methods: {
        isObject(target) {
            return is_object(target)
        },

        isComponent(component, componentName) {
            return (
                component.hasOwnProperty('type') &&
                component.type == componentName
            )
        },
        

    },
}
</script>
