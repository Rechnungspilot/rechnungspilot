<template>
    <div class="modal fade" tabindex="-1" role="dialog" id="transaction-edit">
        <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Buchung bearbeiten</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Datum</label>
                            <datetime v-model="date" input-class="form-control" format="dd.MM.yyyy"></datetime>
                        </div>
                        <div class="form-group">
                            <label>Betrag</label>
                            <currency-input v-model="amount" :error="'amount' in errors ? errors.amount[0] : ''" :readonly="transaction.iban != ''"></currency-input>
                        </div>
                        <tag-select class="my-2" :selected="transaction.tags" type="buchungen" :type_id="transaction.id" v-if="transaction != undefined"></tag-select>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="align-middle" width="10%">&nbsp;</th>
                                        <th class="align-middle" width="15%">Beleg</th>
                                        <th class="align-middle" width="15%">Kontakt</th>
                                        <th class="align-middle" width="10%">Datum</th>
                                        <th class="align-middle" width="10%">Fällig</th>
                                        <th class="align-middle text-right" width="10%">Brutto</th>
                                        <th class="align-middle text-right" width="10%">Offen</th>
                                        <th class="align-middle" width="15%">Betrag</th>
                                        <th class="align-middle" width="15%">Erledigt?</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="pointer" v-for="payment in transaction.payments">
                                        <td class="align-middle text-center" v-if="isSelected(payment.receipt.id)" @click="toggleReceiptId(payment.receipt.id, payment.receipt.outstanding)">
                                            <i class="fas fa-fw fa-check-circle text-success"></i>
                                        </td>
                                        <td class="align-middle text-center" v-else @click="toggleReceiptId(payment.receipt.id, payment.receipt.outstanding)">
                                            <i class="fas fa-fw fa-circle text-light"></i>
                                        </td>
                                        <td class="align-middle" @click="toggleReceiptId(payment.receipt.id, payment.receipt.outstanding)">
                                            {{ payment.receipt.name }}<br />
                                            <span class="text-muted">{{ payment.receipt.typeName }}</span>
                                        </td>
                                        <td class="align-middle" @click="toggleReceiptId(payment.receipt.id, payment.receipt.outstanding)">{{ payment.receipt.contact.name }}</td>
                                        <td class="align-middle" @click="toggleReceiptId(payment.receipt.id, payment.receipt.outstanding)">{{ dateFormat(payment.receipt.date) }}</td>
                                        <td class="align-middle" @click="toggleReceiptId(payment.receipt.id, payment.receipt.outstanding)">{{ dateFormat(payment.receipt.dateDue) }}</td>
                                        <td class="align-middle text-right" @click="toggleReceiptId(payment.receipt.id, payment.receipt.outstanding)">{{ (payment.receipt.gross / 100).format(2, ',', '.') }}</td>
                                        <td class="align-middle text-right" @click="setAmount(payment.receipt.id, payment.receipt.outstanding)">{{ (payment.receipt.outstanding / 100).format(2, ',', '.') }}</td>
                                        <td class="align-middle"><currency-input v-model="receiptIds[payment.receipt.id].amount" v-if="isSelected(payment.receipt.id)" @input="checkCompleted(payment.receipt.id, payment.receipt.outstanding)"></currency-input></td>
                                        <td class="align-middle">
                                            <label class="form-checkbox"></label>
                                            <input type="checkbox" v-model="receiptIds[payment.receipt.id].completed" v-if="isSelected(payment.receipt.id)">
                                        </td>
                                    </tr>
                                    <tr class="pointer" v-for="(item, index) in items" v-show="originalReceiptIds.indexOf(item.id) == -1">
                                        <td class="align-middle text-center" v-if="isSelected(item.id)" @click="toggleReceiptId(item.id, item.outstanding)">
                                            <i class="fas fa-fw fa-check-circle text-success"></i>
                                        </td>
                                        <td class="align-middle text-center" v-else @click="toggleReceiptId(item.id, item.outstanding)">
                                            <i class="fas fa-fw fa-circle text-light"></i>
                                        </td>
                                        <td class="align-middle" @click="toggleReceiptId(item.id, item.outstanding)">
                                            {{ item.name }}<br />
                                            <span class="text-muted">{{ item.typeName }}</span>
                                        </td>
                                        <td class="align-middle" @click="toggleReceiptId(item.id, item.outstanding)">{{ item.contact.name }}</td>
                                        <td class="align-middle" @click="toggleReceiptId(item.id, item.outstanding)">{{ dateFormat(item.date) }}</td>
                                        <td class="align-middle" @click="toggleReceiptId(item.id, item.outstanding)">{{ dateFormat(item.dateDue) }}</td>
                                        <td class="align-middle text-right" @click="toggleReceiptId(item.id, item.outstanding)">{{ (item.gross / 100).format(2, ',', '.') }}</td>
                                        <td class="align-middle text-right" @click="setAmount(item.id, item.outstanding)">{{ (item.outstanding / 100).format(2, ',', '.') }}</td>
                                        <td class="align-middle"><currency-input v-model="receiptIds[item.id].amount" v-if="isSelected(item.id)" @input="checkCompleted(item.id, item.outstanding)"></currency-input></td>
                                        <td class="align-middle">
                                            <label class="form-checkbox"></label>
                                            <input type="checkbox" v-model="receiptIds[item.id].completed" v-if="isSelected(item.id)">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" @click="update">Speichern</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import moment from "moment";
    import currencyInput from '../form/input/currency.vue';

    export default {

        components: {
            currencyInput,
        },

        props: [
            'transaction',
        ],

        data() {
            return {
                uri: '/buchungen',
                items: [],
                receiptIds: {},
                originalReceiptIds: [],
                amount: 0,
                date: "0",
                errors: {},
            };
        },

        watch: {
            transaction (newValue, oldValue) {
                this.amount = newValue.amount / 100;
                this.date = moment.parseZone(newValue.date).format();
                this.receiptIds = [];
                for (var index in newValue.payments) {
                    Vue.set(this.receiptIds, newValue.payments[index].receipt.id, {
                        amount: newValue.payments[index].data.amount / 100,
                        completed: newValue.payments[index].data.completed || false,
                        payment_id: newValue.payments[index].id,
                    });
                    this.originalReceiptIds.push(newValue.payments[index].receipt.id);
                }
            },
        },

        mounted() {
            this.fetch();
        },

        methods: {
            fetch() {
                var component = this;
                axios.get(this.uri + '/belege')
                    .then( function (response) {
                        component.items = response.data;
                });
            },
            dateFormat(value) {
                return moment(value).format('DD.MM.YYYY');
            },
            update() {
                var component = this;
                axios.put(component.uri + '/' + component.transaction.id, {
                    amount: component.amount,
                    date: component.dateFormat(component.date),
                    receipt_ids: component.receiptIds,
                }).then( function (response) {
                    component.$emit('updated', response.data);
                });
            },
            isSelected(receiptId) {
                return receiptId in this.receiptIds;
            },
            checkCompleted(receiptId, outstanding) {
                outstanding /= 100;
                console.log(this.receiptIds[receiptId].amount, outstanding);
                if (this.receiptIds[receiptId].amount == outstanding)
                {
                    this.receiptIds[receiptId].completed = true;
                }
                else
                {
                    this.receiptIds[receiptId].completed = false;
                }
            },
            setAmount(receiptId, amount) {
                this.receiptIds[receiptId].amount = amount / 100;
                this.receiptIds[receiptId].completed = true;

            },
            toggleReceiptId(receiptId, outstanding) {
                if (this.isSelected(receiptId)) {
                    this.receiptIds.splice(receiptId, 1);
                }
                else {
                    var index = this.originalReceiptIds.indexOf(receiptId);
                    if (index == -1)
                    {
                        var paymentId = 0;
                        var completed = true;
                    }
                    else {
                        outstanding = this.transaction.payments[index].data.amount;
                        var paymentId = this.transaction.payments[index].id;
                        var completed = this.transaction.payments[index].data.completed;
                    }
                    Vue.set(this.receiptIds, receiptId, {
                        amount: outstanding / 100,
                        completed: completed,
                        payment_id: paymentId,
                    });
                }
            },
        },

    };
</script>