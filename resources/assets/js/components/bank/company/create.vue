<template>
    <div class="modal fade" tabindex="-1" role="dialog" id="bank-company-create">
        <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Bankkonto verknüpfen</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Bank</label>
                            <multiselect v-model="bank" :custom-label="label" track-by="id" :options="banks" :multiple="false" :close-on-select="true" :clear-on-select="false" :preserve-search="true" @search-change="search" @input="clear"></multiselect>
                            <div class="invalid-feedback" v-text="'name' in errors ? errors.name[0] : ''"></div>
                        </div>
                        <div class="form-group" v-if="bank != null">
                            <label>Benutzerkennung</label>
                            <input v-model="username" class="form-control" :class="'username' in errors ? 'is-invalid' : ''" placeholder="Benutzerkennung des Kontos" autofocus></input>
                            <div class="invalid-feedback" v-text="'username' in errors ? errors.username[0] : ''"></div>
                        </div>
                        <div class="form-group" v-if="bank != null">
                            <label>Pin</label>
                            <input type="password" v-model="pin" class="form-control" :class="'pin' in errors ? 'is-invalid' : ''" placeholder="Pin für Onlinebanking" autofocus></input>
                            <div class="invalid-feedback" v-text="'pin' in errors ? errors.pin[0] : ''"></div>
                        </div>
                        <button class="btn btn-secondary" v-if="bank != null" @click="connect">Verbinden</button>
                        <div v-if="tan.show">
                            <div v-html="tan.html"></div>
                            <div class="form-group">
                                <label>Tan</label>
                                <input type="text" v-model="tan.tan" class="form-control" :class="'tan' in errors ? 'is-invalid' : ''" placeholder="Tan für Onlinebanking" autofocus></input>
                                <div class="invalid-feedback" v-text="'tan' in errors ? errors.tan[0] : ''"></div>
                            </div>
                            <button class="btn btn-secondary" @click="submitTan">Tan absenden</button>
                        </div>
                        <div class="mt-3" v-if="accounts.length">
                            <h5>Konten</h5>
                            <table class="table">
                                <tbody>
                                    <tr class="pointer" v-for="(account, index) in accounts" @click="toggleAccount(index, account)">
                                        <td class="align-middle" width="10%" v-if="accountsSelected[index] == null"></td>
                                        <td class="align-middle" width="10%" v-else>
                                            <i class="fas fa-fw fa-check-circle text-success"></i>
                                        </td>
                                        <td class="align-middle" width="90%">
                                            {{ account.iban }}<br />
                                            <span class="text-muted">{{ account.accountNumber }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="form-group" v-if="bank != null">
                                <label for="date">Import ab</label>
                                <datetime v-model="date" value-zone="local" input-class="form-control" id="date"  format="dd.MM.yyyy"></datetime>
                                <div class="invalid-feedback" v-text="'date' in errors ? errors.date[0] : ''"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" @click="create" :disabled="accountsSelected.length == 0">Speichern</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import moment from "moment";
    import Multiselect from 'vue-multiselect'
    import currencyInput from '../../form/input/currency.vue';

    export default {

        components: {
            currencyInput,
            Multiselect,
        },

        props: [
            'transaction',
        ],

        data() {
            return {
                accounts: [],
                accountsSelected: [],
                uri: '/bank',
                name: '',
                errors: {},
                bank: null,
                bankCompanyId: 0,
                username: '',
                pin: '',
                banks: [],
                searchTimeout: null,
                date: moment().startOf('month').format(),
                tan: {
                    action_path: null,
                    html: '',
                    needed: false,
                    tan: '',
                },
            };
        },

        methods: {
            create() {
                var component = this;
                axios.post(component.uri + '/konten', {
                    bank_company_id: component.bankCompanyId,
                    date: moment(component.date).format('DD.MM.YYYY'),
                    accounts: component.accountsSelected,
                    }).then( function (response) {
                        component.bank = null;
                        // component.username = '';
                        // component.pin = '';
                        component.accounts = [];
                        component.accountsSelected = [];
                        for (var index in response.data) {
                            component.$emit('created', response.data[index]);
                        }
                        $('#bank-company-create').modal('hide');
                    }).catch( function (error) {
                        component.errors = error.response.data.errors;
                });
            },
            search(searchtext) {
                var component = this;
                if (component.searchTimeout)
                {
                    clearTimeout(component.searchTimeout);
                    component.searchTimeout = null;
                }
                component.searchTimeout = setTimeout(function () {
                    axios.get(component.uri + '?searchtext=' + searchtext)
                    .then(function (response) {
                        component.banks = response.data;
                    });
                }, 300);
            },
            connect() {
                var component = this;
                component.accounts = [];
                component.accountsSelected = [];
                axios.post(component.uri, {
                        bank_id: component.bank.id,
                        username: component.username,
                        pin: component.pin,
                    }).then( function (response) {
                        console.log(response.data.tan);
                        component.tan = response.data.tan;
                        component.bankCompanyId = response.data.bank_company_id;
                        if (! component.tan.show) {
                            for (var index in response.data.accounts) {
                                component.accounts.push(response.data.accounts[index]);
                                component.accountsSelected.push(response.data.accounts[index]);
                            }
                        }
                    }).catch( function (error) {
                        component.errors = error.response.data.errors;
                        component.accounts = [];
                        component.accountsSelected = [];
                });
            },
            submitTan() {
                var component = this;
                axios.post(component.uri + '/konten/' + component.bankCompanyId + '/tan', component.tan)
                    .then(function (response) {
                        console.log(response);
                        // TODO: antwort überprüfen
                        // component.connect();
                    });
            },
            toggleAccount(index, account) {
                if (this.accountsSelected[index] == null) {
                    Vue.set(this.accountsSelected, index, account);
                }
                else {
                    Vue.set(this.accountsSelected, index, null);
                }
            },
            label({ name, blz }) {
                return `${name} - BLZ: ${blz}`;
            },
            clear() {
                this.bankCompanyId = 0;
                // this.username = '';
                // this.pin = '';
                this.accounts = [];
                this.accountsSelected = [];
            },
        },

    };
</script>