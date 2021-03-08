<template>

    <table-base :is-loading="isLoading" :paginate="paginate" :items-length="items.length" :has-filter="hasFilter()" @creating="create" @paginating="filter.page = $event" @searching="searching($event)">

        <template v-slot:form>

        </template>

        <template v-slot:filter>

            <filter-tags :options="tags" v-model="filter.tags" @input="search()"></filter-tags>

        </template>

        <template v-slot:thead>
            <tr>
                <th width="30">
                    <label class="form-checkbox" for="checkall"></label>
                    <input id="checkall" type="checkbox" v-model="selectAll">
                </th>
                <th width="40%">Name</th>
                <th width="60%">Tags</th>
                <th class="text-right" width="125">Aktion</th>
            </tr>
        </template>

        <template v-slot:tbody>
            <row :item="item" :key="item.id" :is-selected="isSelected(item.id)" v-for="(item, index) in items" @deleted="deleted(index)" @input="toggleSelected"></row>
        </template>

    </table-base>

</template>

<script>
    import row from "./row.vue";
    import filterTags from "../filter/tags.vue";
    import tableBase from '../tables/base.vue';

    import { baseMixin } from "../../mixins/tables/base.js";
    import { paginatedMixin } from "../../mixins/tables/paginated.js";
    import { selectableMixin } from "../../mixins/selectable.js";

    export default {

        components: {
            filterTags,
            row,
            tableBase,
        },

        mixins: [
            baseMixin,
            paginatedMixin,
            selectableMixin,
        ],

        props: {
            tags: {
                type: Array
            },
            types: {
                type: Array
            },
        },

        data () {
            return {
                filter: {
                    tags: [],
                },
                form: {
                    //
                },
            };
        },

        methods: {
            created(item) {
                location.href = item.path;
            },
        },
    };
</script>