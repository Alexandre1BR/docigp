<template>
    <div class="card shadow-sm mb-4 mt-4">
        <div class="text-left card-header">
            <div class="form-row border-bottom">
                <div class="col-12">
                    <div class="mb-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-7">
                                                <h4 class="mb-0">
                                                    {{ title }}
                                                </h4>
                                            </div>

                                            <div class="d-flex justify-content-end col-5 mr-0 pr-4 ">
                                            <div >
                                            <pulse-loader
                                                margin='3px'
                                                color="#0a008a"
                                                :size="'1em'"
                                                v-if="isLoading"
                                                class="pr-2"
                                            >
                                            </pulse-loader>
                                            </div>
                                            <div class="row">
                                            <slot name="widgets"></slot>
                                               <div v-if="!isLoading">
                                                <i
                                                    v-if="isSelected"
                                                    :v-b-toggle="unCollapsed"
                                                    @click="unCollapsed = !unCollapsed"
                                                    class="fa fa-2x fa-align-end"
                                                    :class="{
                                                        'fa-minus-square': unCollapsed,
                                                        'fa-plus-square': collapsed,
                                                        'text-danger': unCollapsed,
                                                        'text-success': collapsed,
                                                    }"
                                                ></i>
                                                </div>
                                            </div>
                                            
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12" v-if="!isLoading">
                                        <p class="m-0">
                                            <small>{{ subTitle }}</small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>

            <b-collapse :id="makeRandomId()" v-model="unCollapsed">
                <div v-if="perPage" class="row">
                    <div class="col-12 card-filters bg-filters py-2">
                        <div class="row">
                            <div v-if="perPage" class="col-6">
                                <input
                                    class="form-control"
                                    :value="filterText"
                                    @input="$emit('input-filter-text', $event)"
                                    placeholder="filtrar"
                                    dusk="filter_input"
                                />
                            </div>

                            <div v-if="perPage" class="col-4">
                                <app-per-page
                                    :value="perPage"
                                    @input="$emit('set-per-page', $event)"
                                ></app-per-page>
                            </div>

                            <div class="col-2 d-flex justify-content-end mt-1">
                                <slot name="buttons"></slot>

                                <router-link
                                    v-if="addButton"
                                    :to="addButton.uri"
                                    tag="button"
                                    class="btn btn-primary pull-right"
                                    :disabled="addButton.disabled"
                                    :dusk="addButton.dusk"
                                >
                                    <i class="fa fa-plus"></i>
                                </router-link>
                            </div>

                            <div class="col-12" v-if="hasCheckboxes()">
                                <div
                                    :class="
                                        'p-2 mb-2 mt-2 bg-gray-light' +
                                            (dontCenterFilters
                                                ? ''
                                                : ' text-center')
                                    "
                                >
                                    <slot name="checkboxes"></slot>
                                </div>
                            </div>

                            <div class="col-12" v-if="hasSelects()">
                                <div class="p-12 mb-2 mt-2">
                                    <slot name="selects"></slot>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </b-collapse>
        </div>

        <b-collapse  :id="makeRandomId()" v-model="unCollapsed" class="mt-2">
            <div class="row">            
                <div  class="col-12"><slot></slot></div>
            </div>
        </b-collapse>

        <b-collapse :id="makeRandomId()" v-model="collapsed" class="mt-2">
        
            <div
                class="row cursor-pointer"
                :v-b-toggle="unCollapsed"
                @click="unCollapsed = !unCollapsed"
            >
                <div class="col-12 text-center">
                    <h4>
                        {{ makeCollapsedLabel() }}
                    </h4>
                </div>
            </div>
        </b-collapse>
    </div>
</template>

<script>
export default {
    props: [
        'title',
        'titleCollapsed',
        'subTitle',
        'add-button',
        'add-button-disabled',
        'columns',
        'filter-text',
        'per-page',
        'dont-center-filters',
        'collapsedLabel',
        'is-selected',
        'isLoading',
    ],

    data() {
        return {
            unCollapsed: true,
        }
    },

    methods: {
        hasSelects() {
            return this.hasSlot('selects')
        },

        hasCheckboxes() {
            return this.hasSlot('checkboxes')
        },

        hasSlot(name) {
            return !!this.$slots[name] || !!this.$scopedSlots[name]
        },

        makeRandomId() {
            return Math.floor(Math.random() * 1000000).toString()
        },

        makeCollapsedLabel() {
            return this.collapsedLabel
                ? this.collapsedLabel
                : 'nada selecionado'
        },
    },
    
    computed: {      
        collapsed: {
            get() {
                if (!this.collapsedLabel) {
                    this.unCollapsed = true
                }
                return !this.unCollapsed
            },

            set(value) {
                this.unCollapsed = !value
            },
        },
    },
}
</script>
