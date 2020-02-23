<template>
    <tr>
        <td class="align-middle">{{ date }}</td>
        <td class="align-middle text-right" :class="{'text-danger': item.type == 'debit', 'text-success': item.type == 'credit'}">{{ amount.format(2, ',', '.') }} €</td>
    </tr>
</template>

<script>
    import moment from "moment";

    export default {

        props: [ 'item', 'uri' ],

        computed: {
            date() {
                return moment(this.item.date).format('DD.MM.YYYY');
            },
            amount() {
                return this.item.amount / 100 * (this.item.type == 'debit' ? -1 : 1);
            }
        },

        methods: {

        },
    };
</script>