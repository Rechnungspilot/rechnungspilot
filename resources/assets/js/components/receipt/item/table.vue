<template>

    <table-base :is-loading="isLoading" :is-showing-footer="true" has :items-length="items.length" :has-filter="hasFilter()" @searching="searching($event)" @creating="create">

        <template v-slot:form>
            <div class="form-group mb-0 mr-1">
                <select v-model="form.item_id" class="form-control form-control-sm" :class="error('item_id') ? 'is-invalid' : ''" id="item_id">
                    <option value="0">Artikel hinzufügen</option>
                    <option v-for="(option, key) in options" :value="option.id">{{ option.name }}</option>
                </select>
                <div class="invalid-feedback" v-text="error('item_id') ? error('item_id') : ''"></div>
            </div>
        </template>

        <template v-slot:thead>
            <tr>
                <th width="30">
                    <label class="form-checkbox" for="checkall"></label>
                    <input id="checkall" type="checkbox" v-model="selectAll">
                </th>
                <th width="20%">Beschreibung</th>
                <th class="text-right" width="10%">Menge</th>
                <th width="15%">Einheit</th>
                <th class="text-right" width="10%">Preis</th>
                <th class="text-right" width="10%">%</th>
                <th class="text-right" width="10%" v-if="model.company.sales_tax">Ust.</th>
                <th class="text-right" width="10%">Betrag</th>
                <th class="text-right" width="5%"></th>
                <th class="text-right" width="125"></th>
            </tr>
        </template>

        <template v-slot:tbody>
            <row :item="item" :key="item.id" :company="model.company" :units="units" :is-selected="isSelected(item.id)" v-for="(item, index) in items" @deleted="deleted(index)" @updated="updated(index, $event)" @input="toggleSelected"></row>
        </template>

        <template v-slot:tfoot>
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
        </template>

    </table-base>

</template>

<script>
    import row from "./row.vue";
    import tableBase from '../../tables/base.vue';

    import { baseMixin } from "../../../mixins/tables/base.js";
    import { selectableMixin } from "../../../mixins/selectable.js";

    export default {

        components: {
            row,
            tableBase,
        },

        mixins: [
            baseMixin,
            selectableMixin,
        ],

        props: [
            'model',
            'options',
            'units',
        ],

        props: {
            model: {
                required: true,
                type: Object,
            },
            options: {
                required: true,
                type: Array,
            },
            units: {
                required: true,
                type: Array,
            },
        },

        data () {
            return {
                action: '0',
                errors: {},
                net: this.model.net,
                gross: this.model.gross,
                form: {
                    item_id: 0,
                },
            };
        },

        watch: {
            action(newValue, oldValue) {
                if (newValue == 0) {
                    return;
                }

                this[newValue]();
            },
        },

        computed: {
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
            resetForm() {
                this.form.item_id = 0;
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
        },
    };
</script>