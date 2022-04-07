<template>
    <div>

    <div class="row justify-content-center">
        <div class="col-12">
            <h1 class="text-center">Prestação de Contas</h1>
        </div>

        <div class="col-sm-12 col-md-5">
            <app-congressmen></app-congressmen>
        </div>

        <div class="col-sm-12 col-md-7">
            <app-congressman-budgets
                v-if="congressmen.selected.id"
            ></app-congressman-budgets>
        </div>

        <div class="col-sm-12">
            <app-entries v-if="congressmanBudgets.selected.id"></app-entries>
        </div>

        <div class="col-sm-12 col-md-6">
            <app-documents v-if="entries.selected.id"></app-documents>
        </div>

        <div class="col-sm-12 col-md-6">
            <app-comments v-if="entries.selected.id"></app-comments>
        </div>
    </div>
    
    </div>
</template>

<script>
import entries from '../../views/mixins/entries'
import congressmen from '../../views/mixins/congressmen'
import congressmanBudgets from '../../views/mixins/congressmanBudgets'

export default {
    mixins: [congressmen, congressmanBudgets, entries],
}
</script>
