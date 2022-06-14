<template>
    <div>
        <label :for="name" class="mb-0 mt-2">{{ label }}</label>

        <vue-select
            v-model="selected"
            :id="name"
            :name="name"
            :required="required"
            :options="makeOptions(options)"
            :disabled="readonly || disabled()"
            :placeholder="placeholder()"
        >
        </vue-select>

        <small class="text-danger" v-if="form.errors.has(name)">
            <i class="fas fa-exclamation-triangle"></i>
            {{ form.errors.get(name) }}
        </small>
    </div>
</template>

<script>
export default {
    props: [
        'value',
        'name',
        'label',
        'required',
        'form',
        'options',
        'readonly',
        'labelAttribute',
    ],

    methods: {
        makeOptions(options) {
            options = options.rows ? options.rows : options

            return _.map(options, value => {
                return this.makeObject(value)
            })
        },

        select(value) {
            this.$emit('input', value)
        },

        findById(id) {
            if (!this.options) {
                return { name: null, id: null }
            }

            return _.find(this.getOptionsList(), value => {
                return value.id === id
            })
        },

        getOptionsList() {
            if (this.options.hasOwnProperty('rows')) {
                return this.options.rows
            }

            return this.options
        },

        makeObject(value) {
            if (!value) {
                return null
            }

            return {
                label: value[this.makeLabelAttributeName()],
                value: value.id,
            }
        },

        disabled() {
            return this.options.length === 0
        },

        placeholder() {
            return this.options.length === 0 ? 'NENHUM ITEM DISPONÍVEL' : ''
        },

        makeLabelAttributeName() {
            //Default label attribute is name
            return this.labelAttribute ? this.labelAttribute : 'name'
        },
    },

    computed: {
        selected: {
            get() {
                return this.makeObject(this.findById(this.value))
            },
            set(item) {
                if (!item) {
                    return this.$emit('input', null)
                } else {
                    return this.$emit('input', item.value)
                }
            },
        },
    },
}
</script>
