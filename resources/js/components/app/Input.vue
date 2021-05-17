<template>
    <div
        v-bind:class="{
            'm-0': type === 'checkbox',
            inline: type === 'checkbox' && inline,
            'text-right': type === 'money',
        }"
        style="white-space: nowrap"
    >
        <label v-if="type !== 'checkbox'" :for="name" class="mb-0 mt-2">
            {{ label }}
        </label>

        <input
            v-if="type !== 'money'"
            :value="value"
            @input="
                type !== 'checkbox' ? $emit('input', $event.target.value) : null
            "
            @change="
                type === 'checkbox'
                    ? $emit('input', $event.target.checked)
                    : null
            "
            :class="type !== 'checkbox' ? 'form-control' : 'form-check-input'"
            :id="name"
            :type="type"
            :required="required"
            :dusk="dusk"
            :readonly="readonly"
            :checked="value"
            @keyup="$emit('on-key-up')"
            ref="input"
        />

        <money
            v-if="type === 'money'"
            class="form-control"
            :value="numericValue"
            @input="onChange($event)"
            v-bind="money"
            :readonly="readonly"
            dir="rtl"
            ref="money"
            dusk="dusk_value"
        ></money>

        <small
            class="validation-error text-danger"
            v-if="form.errors.has(name)"
        >
            <i class="fas fa-exclamation-triangle"></i>
            {{ form.errors.get(name) }}
        </small>

        <small :class="bottomMessageClasses" v-if="bottomMessage">
            <i
                v-if="bottomMessageType === 'error'"
                class="fas fa-exclamation-triangle"
            ></i>
            {{ bottomMessage }}
        </small>

        <label
            v-if="type === 'checkbox'"
            :for="name"
            class="form-check-label d-inline-block"
        >
            {{ label }}
        </label>
    </div>
</template>

<script>
import { VMoney } from 'v-money'

export default {
    directives: { money: VMoney },

    props: [
        'value',
        'name',
        'label',
        'required',
        'form',
        'type',
        'dusk',
        'readonly',
        'checked',
        'inline',
        'bottomMessage',
        'bottomMessageType',
        'dummy',
        'focus',
    ],

    data() {
        return {
            money: {
                decimal: ',',
                thousands: '.',
                prefix: 'R$ ',
                suffix: '',
                precision: 2,
                masked: false,
            },
        }
    },

    methods: {
        setFocus() {
            if (!this.focus) {
                return
            }

            const ref = this.type === 'money' ? 'money' : 'input'

            this.$refs[ref].focus() // TODO: make this work
        },

        onChange(event) {
            this.$emit('input', event.target ? event.target.value : event)
        },
    },

    computed: {
        bottomMessageClasses() {
            return {
                'validation-error': this.bottomMessageType === 'error',
                'text-danger': this.bottomMessageType === 'error',

                'text-primary': this.bottomMessageType !== 'error',
            }
        },

        numericValue() {
            if (this.value === null) {
                return 0
            }

            if (typeof this.value === 'string') {
                return parseFloat(this.value)
            }

            return this.value
        },
    },

    created() {
        setTimeout(x => {
            this.$nextTick(() => this.setFocus()) // this works great, just watch out with going to fast !!!
        }, 1000)
    },
}
</script>
