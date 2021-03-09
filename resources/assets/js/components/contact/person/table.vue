<template>

    <table-base :is-loading="isLoading" :items-length="items.length" :has-filter="hasFilter()" @searching="searching($event)" @creating="create">

        <template v-slot:form>

        </template>

        <template v-slot:thead>
            <tr>
                <th class="align-middle" width="10%">Anrede</th>
                <th class="align-middle" width="15%">Nachname</th>
                <th class="align-middle" width="15%">Vorname</th>
                <th class="align-middle" width="10%">Telefon</th>
                <th class="align-middle" width="10%">Mobil</th>
                <th class="align-middle" width="15%">E-Mail</th>
                <th class="align-middle" width="9%">Funktion</th>
                <th class="align-middle" width="8%">Standard Angebot</th>
                <th class="align-middle" width="8%">Standard Rechnung</th>
                <th class="text-right" width="100">Aktion</th>
            </tr>
        </template>

        <template v-slot:tbody>
            <row :item="item" :key="item.id" v-for="(item, index) in items" @deleted="deleted(index)" @setDefault="setDefault" @updated="updated(index, $event)"></row>
        </template>

    </table-base>

</template>

<script>
    import row from "./row.vue";
    import tableBase from '../../tables/base.vue';

    import { baseMixin } from "../../../mixins/tables/base.js";

    export default {

        components: {
            row,
            tableBase,
        },

        mixins: [
            baseMixin,
        ],

        data () {
            return {

            };
        },

        methods: {
            setDefault(type, item_id, value) {
                for (var key in this.items) {
                    this.items[key]['default_' + type] = (this.items[key].id == item_id && value) ? 1 : 0;
                }
            }
        },
    };
</script>