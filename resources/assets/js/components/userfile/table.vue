<template>

    <div>

        <div class="row mb-3 px-3">
            <create @created="created"></create>
        </div>

        <table-base :has-create-button="false" :is-loading="isLoading" :paginate="paginate" :items-length="items.length" :has-filter="hasFilter()" @creating="create" @paginating="filter.page = $event" @searching="searching($event)">

            <template v-slot:form>

            </template>

            <template v-slot:filter>

                <filter-type :options="types" v-model="filter.type" @input="search()"></filter-type>
                <filter-tags :options="tags" v-model="filter.tags" @input="search()"></filter-tags>

            </template>

            <template v-slot:thead>
                <tr>
                    <tr>
                        <th width="40%">Bezeichnung</th>
                        <th width="30%">Datensatz</th>
                        <th width="30%">Tags</th>
                        <th class="text-right" width="125">Aktion</th>
                    </tr>
                </tr>
            </template>

            <template v-slot:tbody>
                <row :item="item" :key="item.id" :tags="tags" v-for="(item, index) in items" @deleted="deleted(index)" @updated="updated(index, $event)"></row>
            </template>

        </table-base>

    </div>

</template>

<script>
    import create from "./create.vue";
    import row from "./row.vue";
    import filterType from "../filter/type.vue";
    import filterTags from "../filter/tags.vue";
    import tableBase from '../tables/base.vue';

    import { baseMixin } from "../../mixins/tables/base.js";
    import { paginatedMixin } from "../../mixins/tables/paginated.js";

    export default {

        components: {
            create,
            filterTags,
            filterType,
            row,
            tableBase,
        },

        mixins: [
            baseMixin,
            paginatedMixin,
        ],

        props: {
            tags: {
                type: Array
            },
            token: {
                required: true,
                type: String,
            },
            types: {
                type: Object
            },
        },

        data () {
            return {
                filter: {
                    type: '',
                    tags: [],
                },
            };
        },

        methods: {
            created(files) {
                for (var index in files) {
                    this.items.unshift(files[index]);
                }
            },
        },
    };
</script>

<style>
    .filezone {
        outline: 2px dashed grey;
        outline-offset: -10px;
        background: #ccc;
        color: dimgray;
        padding: 10px 10px;
        min-height: 200px;
        position: relative;
        cursor: pointer;
    }
    .filezone:hover {
        background: #c0c0c0;
    }
</style>