<template>
    <tr>
        <td class="align-middle">{{ date }}</td>
        <td class="align-middle">
            {{ item.name }}<br />
            <span class="text-muted">{{ item.iban }}</span>
        </td>
        <td class="align-middle">{{ item.type }}</td>
        <td class="align-middle">{{ (item.amount / 100).format(2, ',', '.') }}</td>
        <td class="align-middle">{{ item.text }}</td>
        <td class="align-middle">
            <select class="form-control" v-model="companyId" v-show="transactionableId == 0">
                <option value="0">Firma wählen</option>
                <option :value="company.id" v-for="company in companies">{{ company.name }}</option>
            </select>
        </td>
        <td class="align-middle">
            <button class="btn btn-secondary" v-show="companyId > 0 && transactionableId == 0" @click="create"><i class="fas fa-fw fa-save"></i></button>
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
        },
    };
</script>