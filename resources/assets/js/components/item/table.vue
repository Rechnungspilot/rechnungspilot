<template>

    <table-paginated :is-loading="isLoading" :paginate="paginate" :items-length="items.length" :has-filter="hasFilter()" @creating="create" @paginating="filter.page = $event" @searching="searching($event)">

        <template v-slot:form>
            <div class="form-group mb-0 mr-1">
                <input-text v-model="form.name" placeholder="Name" :error="error('name')" @keydown.enter="create"></input-text>
            </div>
        </template>

        <template v-slot:filter>

            <filter-type :options="types" v-model="filter.type" @input="search()"></filter-type>
            <filter-tags :options="tags" v-model="filter.tags" @input="search()"></filter-tags>
            <filter-per-page v-model="filter.perPage" @input="search()"></filter-per-page>

        </template>

        <template v-slot:thead>
            <tr>
                <th width="30">
                    <label class="form-checkbox" for="checkall"></label>
                    <input id="checkall" type="checkbox" v-model="selectAll">
                </th>
                <th class="text-right" width="85">#</th>
                <th width="100%">Name</th>
                <th class="text-right" width="75">Brutto</th>
                <th class="text-right" width="75">Netto</th>
                <th width="75">Einheit</th>
                <th width="75">USt.</th>
                <th class="text-right" width="75">Umsatz</th>
                <th class="text-right" width="125">Aktion</th>
            </tr>
        </template>

        <template v-slot:tbody>
            <row :item="item" :key="item.id" :is-selected="isSelected(item.id)" v-for="(item, index) in items" @deleted="deleted(index)" @input="toggleSelected"></row>
        </template>

    </table-paginated>

</template>

<script>
    import row from "./row.vue";
    import filterTags from "../filter/tags.vue";
    import filterType from "../filter/itemtype.vue";
    import filterPerPage from "../filter/perPage.vue";
    import inputText from '../form/input/text.vue';
    import tablePaginated from '../tables/paginated.vue';

    import { baseMixin } from "../../mixins/tables/base.js";
    import { paginatedMixin } from "../../mixins/tables/paginated.js";
    import { selectableMixin } from "../../mixins/selectable.js";

    export default {

        components: {
            row,
            filterTags,
            filterType,
            filterPerPage,
            inputText,
            tablePaginated
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
                    type: -1,
                },
                form: {
                    name: '',
                },
            };
        },

        methods: {
            created(item) {
                location.href = item.edit_path;
            },
        },
    };
</script>