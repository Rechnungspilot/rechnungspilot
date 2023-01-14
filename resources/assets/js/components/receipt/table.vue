<template>

    <table-base :is-loading="isLoading" :is-showing-footer="selected.length > 0" :paginate="paginate" :items-length="items.length" :has-filter="hasFilter()" @creating="create" @paginating="filter.page = $event" @searching="searching($event)">

        <template v-slot:form>

        </template>

        <template v-slot:filter>

            <filter-contact :options="contacts" v-model="filter.contact_id" @input="fetch"></filter-contact>
            <filter-status :options="statuses" v-model="filter.status_type" @input="fetch"></filter-status>
            <filter-tags :options="tags" v-model="filter.tags" @input="fetch"></filter-tags>
            <filter-year v-model="filter.year" @input="fetch"></filter-year>
            <filter-per-page v-model="filter.perPage" @input="fetch"></filter-per-page>

        </template>

        <template v-slot:thead>
            <tr>
                <th class="text-center" width="30">
                    <label class="form-checkbox" for="checkall"></label>
                    <input id="checkall" type="checkbox" v-model="selectAll">
                </th>
                <th width="100">Datum</th>
                <th width="50%">#</th>
                <th width="50%">Empfänger</th>
                <th class="text-right" width="100">Netto</th>
                <th class="text-right" width="100">Brutto</th>
                <th width="100">Status</th>
                <th class="text-right" width="125">Aktion</th>
            </tr>
        </template>

        <template v-slot:tbody>
            <row :item="item" :key="item.id" :is-selected="isSelected(item.id)" v-for="(item, index) in items" @deleted="deleted(index)" @input="toggleSelected"></row>
        </template>

        <template v-slot:tfoot>
            <tr>
                <td class="align-middle" colspan="3">{{ selected.length }} ausgewählt</td>
                <td></td>
                <td></td>
                <td class="align-middle text-right">{{ sum_gross.format(2, ',', '.') }}</td>
                <td class="align-middle" colspan="3">
                    <select class="form-control" v-model="action">
                        <option value="0">Aktion</option>
                        <option value="downloadPdfs">PDFs herunterladen</option>
                        <optgroup label="Export">
                            <option value="exportDatevEinzeln">Datev</option>
                        </optgroup>
                    </select>
                </td>
            </tr>
        </template>

    </table-base>

</template>

<script>
    import filterContact from "../filter/contact.vue";
    import filterPerPage from "../filter/perPage.vue";
    import filterSearch from "../filter/search.vue";
    import filterStatus from "../filter/status.vue";
    import filterTags from "../filter/tags.vue";
    import filterYear from "../filter/year.vue";
    import row from "./row.vue";
    import tableBase from '../tables/base.vue';

    import { baseMixin } from "../../mixins/tables/base.js";
    import { paginatedMixin } from "../../mixins/tables/paginated.js";
    import { selectableMixin } from "../../mixins/selectable.js";

    export default {

        components: {
            filterContact,
            filterPerPage,
            filterSearch,
            filterStatus,
            filterTags,
            filterYear,
            row,
            tableBase,
        },

        mixins: [
            baseMixin,
            paginatedMixin,
            selectableMixin,
        ],

        props: {
            contacts: {
                type: Array,
                required: true,
            },
            statuses: {
                type: Object,
                required: true,
            },
            tags: {
                type: Array,
                required: true,
            },
        },

        computed: {
            sum_gross() {
                var component = this;
                return this.items.reduce( function(sum, item) {
                    if (! component.isSelected(item.id)) {
                        return sum + 0;
                    }
                    return sum + Number(item.gross / 100);
                }, 0);
            },
        },

        data () {
            return {
                action: '0',
                filter: {
                    contact_id: 0,
                    status_type: 0,
                    tags: [],
                    year: (new Date()).getFullYear(),
                    perPage: 25,
                },
            };
        },

        watch: {
            action(newValue, oldValue) {
                if (newValue == 0) {
                    return;
                }

                this[newValue]();
            },
        },

        methods: {
            created(item) {
                location.href = item.edit_path;
            },
            setInitialFilters() {
                for (var key in this.filter) {
                    if (key in this.initialFilter) {
                        this.filter[key] = this.initialFilter[key];
                    }
                }
            },
            downloadPdfs() {
                var component = this;
                axios.post('belege/pdfs', {
                    receipt_ids: component.selected,
                })
                    .then(function (response) {
                        location.href = response.data.path;
                    })
                    .catch(function (error) {
                        Vue.error('PDFs konnten nicht erstellt werden!');
                    })
                    .then( function() {
                        component.action = '0';
                });
            },
            exportDatevEinzeln() {
                var component = this;
                axios.post('belege/exporte/datev/einzeln', {
                    receipt_ids: component.selected,
                })
                    .then(function (response) {
                        location.href = response.data.path;
                    })
                    .catch(function (error) {
                        Vue.error('Export konnte nicht erstellt werden!');
                    })
                    .then( function() {
                        component.action = '0';
                });
            },
        },
    };
</script>