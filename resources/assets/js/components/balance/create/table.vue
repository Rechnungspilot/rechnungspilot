<template>
    <div>
        <table class="table table-hover table-striped bg-white" v-if="items.length">
            <thead>
                <tr>
                    <th width="10%">Datum</th>
                    <th width="25%">Sender</th>
                    <th width="10%">Typ</th>
                    <th width="10%">Betrag</th>
                    <th width="30%">Verwendungszweck</th>
                    <th width="10%">Zuordnen</th>
                    <th width="5%"></th>
                </tr>
            </thead>
            <tbody>
                <row v-for="(item, index) in items" :item="item" :companies="companies" :key="item.id" :uri="uri" @updated="update(index, $event)"></row>
            </tbody>
        </table>
        <div class="alert alert-dark" v-else><center>Keine Buchungen vorhanden</center></div>
    </div>
</template>

<script>
    import row from "./row.vue";

    export default {

        components: {
            row,
        },

        props: [
            'companies',
            'transactions',
        ],

        data () {
            return {
                uri: '/guthaben',
                items: this.transactions,
            };
        },

        methods: {
            remove(index) {
                this.items.splice(index, 1);
            },
            update(index, item) {
                this.items[index] = item;
            },
        },
    };
</script>