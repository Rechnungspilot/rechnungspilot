<template>
    <tr>
        <td class="align-middle">
            <label class="form-checkbox"></label>
            <input :checked="selected" type="checkbox" :value="id"  @change="$emit('input', id)" number>
        </td>
        <td class="align-middle">
            <div>{{ formatDate(item.date) }}</div>
            <div>{{ formatDate(item.date_due) }}</div>
        </td>
        <td class="align-middle pointer"><a :href="item.path" target="_blank">{{ item.name }}</a></td>
        <td class="align-middle"><a :href="'/kontakte/' + item.contact.id">{{ item.contact.name }}</a></td>
        <td class="align-middle text-right">{{ (item.gross / 100).format(2, ',', '.') }}</td>
        <td class="align-middle text-right pointer" @click="form.amount = (item.outstanding / 100)">{{ (item.outstanding / 100).format(2, ',', '.') }}</td>
        <td class="align-middle">
            <currency-input v-model="form.amount" :error="'amount' in errors ? errors.amount[0] : ''"></currency-input>
        <td class="align-middle">
            <date-input v-model="date" :error="(('date' in errors) ? errors.date[0] : '')"></date-input>
        </td>
        <td class="align-middle">
            <select class="form-control" v-model="form.account_id">
                <option :value="account.id" v-for="account in accounts">{{ account.name }}</option>
            </select>
        </td>
        <td class="align-middle text-center">
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" v-model="form.completed" :id="'completed-' + item.id">
                <label class="form-check-label" :for="'completed-' + item.id"></label>
            </div>
        </td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-secondary" title="Zahlung erfassen" @click="create"><i class="fas fa-save"></i></button>
            </div>
        </td>
    </tr>
</template>

<script>
    import moment from "moment";

    import currencyInput from '../../form/input/currency.vue';

    export default {

        components: {
            currencyInput,
        },

        props: [
            'accounts',
            'item',
            'uri',
            'selected'
        ],

        data () {
            return {
                id: this.item.id,
                form: {
                    amount: Number(this.item.outstanding / 100),
                    date: '',
                    type: 'App\\Receipts\\Statuses\\Payment',
                    account_id: this.accounts[0].id,
                    completed: true,
                },
                date: moment(this.item.date_due).toISOString(),
                errors: {},
            };
        },

        computed: {
            outstanding() {
                return this.item.outstanding;
            },
            amount() {
                return this.form.amount;
            }
        },

        watch: {
            page () {
                this.fetch();
            },
            outstanding(newValue) {
                this.form.amount = Number(newValue / 100);
            },
            amount(newValue) {
                this.form.completed = (newValue * 100 == this.outstanding);
            }
        },

        methods: {
            create() {
                var component = this;
                component.form.date = this.formatDate(component.date);
                axios.post('/belege/status/' + component.id, component.form)
                    .then(function (response) {
                        component.$emit("created", response.data);
                        Vue.success('Zahlung wurde erfasst.');
                    })
                    .catch(function (error) {
                        Vue.error('Zahlung konnte nicht erfasst werden.');
                    });
            },
            link () {
                location.href = this.item.path;
            },
            formatDate(date) {
                return moment(date).format('DD.MM.YYYY');
            },
        },
    };
</script>