<template>
    <div>
        <div class="container-fluid">
            <div class="row">
                <div class="form-group mb-0">
                    <select v-model="form.item_id" class="form-control" :class="'item_id' in errors ? 'is-invalid' : ''" id="item_id">
                        <option value="0">Artikel hinzufügen</option>
                        <option v-for="(option, key) in options" :value="option.id">{{ option.name }}</option>
                    </select>
                    <div class="invalid-feedback" v-text="'item_id' in errors ? errors.item_id[0] : ''"></div>
                </div>
                <button class="btn btn-primary ml-1" :disabled="form.item_id == 0" @click="create"><i class="fas fa-plus-square"></i></button>
            </div>
        </div>
        <br />
        <div v-if="isLoading" class="p-5">
            <center>
                <span style="font-size: 48px;">
                    <i class="fas fa-spinner fa-spin"></i><br />
                </span>
                Lade Daten..
            </center>
        </div>
        <div class="response" v-else-if="items.length">
            <table class="table table-hover table-striped bg-white">
                <thead>
                    <tr>
                        <th width="5%">
                            <label class="form-checkbox" for="checkall"></label>
                            <input id="checkall" type="checkbox" v-model="selectAll">
                        </th>
                        <th width="15%">Beschreibung</th>
                        <th class="text-right" width="10%">Menge</th>
                        <th width="10%">Einheit</th>
                        <th class="text-right" width="10%">Preis</th>
                        <th class="text-right" width="5%">%</th>
                        <th class="text-right" width="10%" v-if="model.company.sales_tax">Ust.</th>
                        <th class="text-right" width="10%">Betrag</th>
                        <th class="text-right" width="5%"></th>
                        <th class="text-right" width="10%"></th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(item, index) in items">
                        <row :item="item" :key="item.id" :selected="(selected.indexOf(item.id) == -1) ? false : true" :company="model.company" :units="units" @deleted="remove(index)" @updated="updated(index, $event)" @input="toggleSelected"></row>
                    </template>
                </tbody>
                <tfoot>
                    <tr v-show="selected.length > 0">
                        <td class="align-middle">{{ selected.length }} ausgewählt</td>
                        <td class="align-middle"></td>
                        <td class="text-right"></td>
                        <td></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td v-if="model.company.sales_tax"></td>
                        <td></td>
                        <td></td>
                        <td class="align-middle">
                            <select class="form-control" v-model="action">
                                <option value="0">Aktion</option>
                                <option value="invoiceCreate">Rechnung erstellen</option>
                                <option value="invoiceAppend" v-if="false">Zu Rechnung hinzufügen</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Zwischensumme</td>
                        <td class="text-right"></td>
                        <td></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td v-if="model.company.sales_tax"></td>
                        <td class="text-right">{{ (net / 100).format(2, ',', '.') }} €</td>
                        <td></td>
                        <td class="text-right"></td>
                    </tr>
                    <tr v-for="tax in taxes"  v-if="model.company.sales_tax">
                        <td></td>
                        <td>Ust.</td>
                        <td class="text-right"></td>
                        <td></td>
                        <td class="text-right">{{ (tax['net'] / 100).format(2, ',', '.') }} €</td>
                        <td></td>
                        <td class="text-right">{{ (tax['tax'] * 100).format(0, ',', '.') }} %</td>
                        <td class="text-right">{{ (tax['value'] / 100).format(2, ',', '.') }} €</td>
                        <td></td>
                        <td class="text-right"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><b>Gesamt</b></td>
                        <td class="text-right"></td>
                        <td></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td v-if="model.company.sales_tax"></td>
                        <td class="text-right"><b>{{ ( (model.company.sales_tax ? gross : net) / 100).format(2, ',', '.') }} €</b></td>
                        <td></td>
                        <td class="text-right"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="alert alert-dark" v-else><center>Keine Positionen vorhanden</center></div>
    </div>
</template>

<script>
    import row from "./row.vue";

    export default {

        components: {
            row,
        },

        props: [
            'model',
            'options',
            'units',
        ],

        data () {
            return {
                id: this.model.id,
                uri: '/belege/' + this.model.id + '/artikel',
                items: [],
                isLoading: true,
                showFilter: true,
                searchtext: '',
                searchTimeout: null,
                selected: [],
                action: '0',
                errors: {},
                net: this.model.net,
                gross: this.model.gross,
                form: {
                    item_id: 0,
                },
            };
        },

        mounted() {

            this.fetch();

        },

        watch: {
            action(newValue, oldValue) {
                if (newValue == 0) {
                    return;
                }

                this[newValue]();
            },
            page () {
                this.fetch();
            },
        },

        computed: {
            selectAll: {
                get: function () {
                    return this.items.length ? this.items.length == this.selected.length : false;
                },
                set: function (value) {
                    this.selected = [];
                    if (value) {
                        for (let i in this.items) {
                            this.selected.push(this.items[i].id);
                        }
                    }
                },
            },
            taxes() {
                var taxes = {};
                var net = 0;
                var gross = 0;
                for (var index in this.items) {
                    var item = this.items[index];
                    if (! (item.tax in taxes)) {
                        taxes[item.tax] = {
                            tax: item.tax,
                            value: 0,
                            net: 0,
                        };
                    }
                    taxes[item.tax]['value'] += item.net * item.tax;
                    taxes[item.tax]['net'] += item.net;
                    net += item.net;
                    gross += item.gross;
                }

                this.net = net;
                this.gross = gross;

                return taxes;
            },
        },

        methods: {
            create() {
                var component = this;
                axios.post(component.uri, component.form)
                    .then(function (response) {
                        response.data['shouldEdit'] = true;
                        component.items.push(response.data);
                        component.form.item_id = 0;
                        Vue.success('Position hinzugefügt.');
                    });
            },
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get(this.uri)
                    .then(function (response) {
                        component.items = response.data;
                        component.isLoading = false;
                    })
                    .catch(function (error) {
                        console.log(error);
                });
            },
            invoiceCreate() {
                var component = this;
                axios.post('/rechnungen/aus/' + this.id, {
                    receipt_item_ids: this.selected,
                })
                    .then(function (response) {
                        location.href = response.data.path;
                    })
                    .catch(function (error) {
                        Vue.error('Positionen konnten nicht abgerechnet werden!');
                    })
                    .then( function() {
                        component.action = '0';
                });
            },
            remove(index) {
                this.items.splice(index, 1);
                Vue.success('Position gelöscht.');
            },
            updated(index, item) {
                Vue.set(this.items, index, item);
                Vue.success('Position gespeichert.');
            },
            toggleSelected (id) {
                var index = this.selected.indexOf(id);
                if (index == -1) {
                    this.selected.push(id);
                }
                else {
                    this.selected.splice(index, 1);
                }
            },

        },
    };
</script>