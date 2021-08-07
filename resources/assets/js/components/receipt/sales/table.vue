<template>

    <table-base :is-loading="isLoading" :is-showing-footer="false" :has-create-button="false" :paginate="paginate" :items-length="items.length" :has-filter="hasFilter()" @creating="create" @paginating="filter.page = $event" @searching="searching($event)">

        <template v-slot:form>

        </template>

        <template v-slot:filter>


        </template>

        <template v-slot:thead>
            <tr>
                <th width="100">Datum</th>
                <th width="100%">Empfänger</th>
                <th class="text-right" width="100">Betrag</th>
                <th class="text-right" width="125">Aktion</th>
            </tr>
        </template>

        <template v-slot:tbody>
            <row :item="item" :key="item.id" :index-path="indexPath" v-for="(item, index) in items" @deleted="deleted(index)"></row>
        </template>

    </table-base>

</template>

<script>
    import row from './row.vue';
    import filterStatus from '../../filter/status.vue';
    import filterContact from '../../filter/contact.vue';
    import filterTags from '../../filter/tags.vue';
    import filterSearch from '../../filter/search.vue';
    import tableBase from '../../tables/base.vue';

    import { baseMixin } from '../../../mixins/tables/base.js';
    import { paginatedMixin } from '../../../mixins/tables/paginated.js';
    import { selectableMixin } from '../../../mixins/selectable.js';

    export default {

        components: {
            filterContact,
            filterSearch,
            filterStatus,
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
            //
        },

        data () {
            return {
                filter: {
                    //
                },
                presold_items: [
                    {
                        name: 'Weidehähnchen',
                        presold_count: 5,
                    },
                ],
            };
        },

        methods: {
            created(item) {
                location.href = item.edit_path;
            },
        },
    };
</script>