<template>
    <div>
        <div class="row mb-3">
            <div class="col d-flex align-items-start mb-1 mb-sm-0">
                <slot name="form"></slot>
                <button class="btn btn-primary btn-sm" @click="$emit('creating')"><i class="fas fa-plus-square"></i></button>
            </div>
            <div class="col-auto d-flex">
                <div class="form-group" style="margin-bottom: 0;">
                    <filter-search v-model="filter.searchtext" @input="$emit('searching', filter.searchtext)"></filter-search>
                </div>
                <button class="btn btn-secondary btn-sm ml-1" @click="filter.show = !filter.show" v-if="hasFilter"><i class="fas fa-filter"></i></button>
            </div>
        </div>

        <form v-if="filter.show" id="filter" class="py-3">
            <div class="form-row">

                <slot name="filter"></slot>

            </div>
        </form>

        <div v-if="isLoading" class="p-5">
            <center>
                <span style="font-size: 48px;">
                    <i class="fas fa-spinner fa-spin"></i><br />
                </span>
                Lade Daten..
            </center>
        </div>
        <table class="table table-fixed table-hover table-striped table-sm bg-white" v-else-if="itemsLength">
            <thead>
                <slot name="thead"></slot>
            </thead>
            <tbody>
                <slot name="tbody"></slot>
            </tbody>
        </table>
        <div class="alert alert-dark" v-else><center>Keine Einheiten vorhanden</center></div>
    </div>
</template>

<script>
    import filterSearch from "../filter/search.vue";

    export default {

        components: {
            filterSearch
        },

        props: {
            itemsLength: {
                type: Number,
                required: true,
            },
            hasFilter: {
                type: Boolean,
                required: false,
                default: false,
            },
            isLoading: {
                type: Boolean,
                required: false,
                default: false,
            },
        },

        data () {
            return {
                filter: {
                    show: false,
                    searchtext: '',
                },
            };
        },

        methods: {

        },
    };
</script>