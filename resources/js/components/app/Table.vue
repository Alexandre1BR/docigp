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
                        <span
                            v-if="isComponent(column, 'label')"
                            v-html="column.title"
                        >
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
