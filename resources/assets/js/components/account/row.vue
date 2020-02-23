<template>
    <tr>
        <td class="align-middle">
            {{ item.name }}
            <div class="text-muted" v-if="item.bank_company_id > 0">{{ item.bank.bank.name }}</div>
        </td>
        <td class="align-middle text-right">{{ item.amount.format(2, ',', '.') }}</td>
        <td></td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm">
                <button class="btn btn-secondary" title="Bearbeiten"><i class="fas fa-fw fa-edit"></i></button>
                <button class="btn btn-secondary" title="Löschen" @click="destroy"><i class="fas fa-fw fa-trash"></i></button>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {

        props: [ 'item', 'uri' ],

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
                var component = this;
                axios.delete(component.item.path)
                    .then( function (response) {
                        component.$emit('deleted');
                    })
                    .catch( function (error) {

                });
            },
        },
    };
</script>