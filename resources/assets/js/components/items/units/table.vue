<template>

    <table-base :is-loading="isLoading" :items-length="items.length" :has-filter="hasFilter()" @searching="searching($event)" @creating="create">

        <template v-slot:form>
            <div class="form-group mb-0 mr-1">
                <input-text v-model="form.name" placeholder="Name" :error="error('name')" @keydown.enter="create"></input-text>
            </div>
            <div class="form-group mb-0 mr-1">
                <input-text v-model="form.abbreviation" placeholder="Abkürzung" :error="error('abbreviation')" @keydown.enter="create"></input-text>
            </div>
        </template>

        <template v-slot:thead>
            <tr>
                <th width="80%">Bezeichnung</th>
                <th class="d-none d-sm-table-cell" width="20%">Abkürzung</th>
                <th class="text-right" width="100">Aktion</th>
            </tr>
        </template>

        <template v-slot:tbody>
            <row :item="item" :key="item.id" v-for="(item, index) in items" @deleted="deleted(index)" @updated="updated(index, $event)"></row>
        </template>

    </table-base>

</template>

<script>
    import row from './row.vue';
    import inputText from '../../form/input/text.vue';
    import tableBase from '../../tables/base.vue';

    import { baseMixin } from "../../../mixins/tables/base.js";

    export default {

        components: {
            inputText,
            row,
            tableBase,
        },

        mixins: [
            baseMixin,
        ],

        data () {
            return {
                filter: {
                    //
                },
                form: {
                    name: '',
                    abbreviation: '',
                },
            };
        },

    };
</script>