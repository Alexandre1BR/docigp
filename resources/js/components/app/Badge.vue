<template>
    <div
        class="badge"
        :class="' p-' + getPadding() + ' m-' + getMargin()"
        :style="{
            'background-color': background,
            color: foreground,
        }"
    >
        <div
            :style="{
                'text-transform': uppercase ? 'uppercase' : 'none',
                'font-size': fontSize,
            }"
        >
            {{ caption }}

            <slot></slot>
        </div>

        <div v-if="complement">
            <div
                style="{'font-size': complementFontSize ? complementFontSize : '0.7em'}"
                class="mt-2"
            >
                <div v-for="part in breakString(complement)">
                    {{ part }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'caption',
        'color',
        'complement',
        'uppercase',
        'padding',
        'margin',
        'fontSize',
        'complementFontSize',
    ],

    computed: {
        background() {
            return this.getColor(0)
        },

        foreground() {
            return this.getColor(1)
        },
    },

    methods: {
        getMargin() {
            return this.margin ? this.margin : 1
        },

        getPadding() {
            return this.padding ? this.padding : 2
        },

        breakString(string) {
            return splitString(string, 30)
        },

        getColor(position) {
            return get_color(this.color, position)
        },
    },
}
</script>
