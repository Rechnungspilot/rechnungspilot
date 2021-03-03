<template>
    <tr>
        <td class="align-middle">{{ date }}</td>
        <td class="align-middle">{{ item.name }}</td>
        <td class="align-middle" v-if="item.payments.length > 0">
            <div v-for="payment in item.payments">
                <a :href="payment.receipt.path" class="text-body">{{ payment.receipt.name }} ({{ (payment.data['amount'] / 100).format(2, ',', '.') }} €)</a>
            </div>
        </td>
        <td class="align-middle" v-else>
            {{ item.reference }}
        </td>
        <td class="align-middle">{{ item.tagsString }}</td>
        <td class="align-middle" :class="{'text-danger': item.type == 'debit', 'text-success': item.type == 'credit'}">{{ amount.format(2, ',', '.') }}</td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-secondary" title="Bearbeiten" @click="$emit('edit', item)"><i class="fas fa-fw fa-edit"></i></button>
            </div>
        </td>
    </tr>
</template>

<script>
    import moment from "moment";

    export default {

        props: [ 'item', 'uri', 'companies' ],

        data () {
            return {
                id: this.item.id,
                companyId: this.item.transactionable_id,
                transactionableId: this.item.transactionable_id
            };
        },

        computed: {
            date() {
                return moment(this.item.date).format('DD.MM.YYYY');
            },
            amount() {
                return this.item.amount / 100 * (this.item.type == 'debit' ? -1 : 1);
            }
        },

        methods: {
            create() {
                var component = this;
                axios.put(component.uri + '/' + component.item.id, {
                    transactionable_id: component.companyId,
                }).then( function (response) {
                    component.transactionableId = component.companyId;
                    component.$emit("updated", response.data);
                });
            },
            destroy() {

            },
        },
    };
</script>