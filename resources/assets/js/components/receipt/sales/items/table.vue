<template>

    <div>

        <div class="form-group">
            <select v-model="form.item_id" class="form-control form-control-sm" :class="error('item_id') ? 'is-invalid' : ''" id="item_id" @change="changedItemId">
                <option value="0">Artikel hinzufügen</option>
                <option v-for="(option, key) in options" :value="option.id">{{ option.name }}</option>
            </select>
        </div>
        <items-articles-list v-model="form.item_article_id" :index-path="selected_item.articles_index_path" :model="selected_item" @input="create" v-if="selected_item != null && selected_item.is_product"></items-articles-list>

        <table-base :is-loading="isLoading" :is-showing-footer="true" :has-create-button="false" :is-searchable="false" :items-length="items.length" :has-filter="hasFilter()" @searching="searching($event)" @creating="create">

            <template v-slot:form>

            </template>

            <template v-slot:thead>
                <tr>
                    <th width="65%">Beschreibung</th>
                    <th class="text-right" width="100">Menge</th>
                    <th width="50">Einheit</th>
                    <th class="text-right" width="100">Betrag</th>
                    <th class="text-right" width="125"></th>
                </tr>
            </template>

            <template v-slot:tbody>
                <row :item="item" :key="item.id" :company="model.company" v-for="(item, index) in items" @deleted="deleted(index)" @updated="updated(index, $event)"></row>
            </template>

            <template v-slot:tfoot>
                <tr>
                    <td class="align-middle ">Gesamt</td>
                    <td class="align-middle text-right" colspan="3">{{ net_formatted }} €</td>
                    <td class="align-middle text-right"></td>
                </tr>
                    <tr v-show="paid > 0 && outstanding > 0">
                        <td class="align-middle ">Gezahlt</td>
                        <td class="align-middle text-right" colspan="3">{{ paid_formatted }} €</td>
                        <td class="align-middle text-right"></td>
                    </tr>
                    <tr v-show="paid > 0 && outstanding > 0">
                        <td class="align-middle ">Offen</td>
                        <td class="align-middle text-right" colspan="3">{{ outstanding_formatted }} €</td>
                        <td class="align-middle text-right"></td>
                    </tr>
                <tr>
                    <td class="align-middle ">Zahlt</td>
                    <td class="align-middle text-right" colspan="3">
                        <input-text v-model="form.pays_formatted" placeholder="Gezahlt" error="" @input="setTipp"></input-text>
                    </td>
                    <td class="align-middle text-right">
                        <span v-show="form.tipp > 0">{{ tipp_formatted }} €</span>
                    </td>
                </tr>
                <tr>
                    <td class="align-middle ">Gegeben</td>
                    <td class="align-middle text-right" colspan="3">
                        <input-text v-model="form.paid_formatted" placeholder="Gegeben" error=""></input-text>
                    </td>
                    <td class="align-middle text-right"></td>
                </tr>
                <tr>
                    <td class="align-middle ">Rückgeld</td>
                    <td class="align-middle text-right" colspan="3">{{ change.format(2, ',', '.') }} €</td>
                    <td class="align-middle text-right"></td>
                </tr>
                <tr>
                    <td class="align-middle text-right" colspan="5">
                        <button class="btn btn-primary btn-sm" @click="update">Speichern</button>
                    </td>
                </tr>
            </template>

        </table-base>

    </div>

</template>

<script>
    import row from './row.vue';
    import inputText from '../../../form/input/text.vue';
    import itemsArticlesList from '../../../items/articles/list.vue';
    import tableBase from '../../../tables/base.vue';

    import { baseMixin } from '../../../../mixins/tables/base.js';

    export default {

        components: {
            inputText,
            itemsArticlesList,
            row,
            tableBase,
        },

        mixins: [
            baseMixin,
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
            modelPath: {
                required: true,
                type: String,
            },
        },

        data () {
            return {
                action: '0',
                errors: {},
                gross: this.model.gross,
                outstanding: this.model.outstanding,
                paid: this.model.net - this.model.outstanding,
                form: {
                    item_id: 0,
                    item_article_id: 0,
                    pays_formatted: (this.model.outstanding / 100).format(2, ',', '.'),
                    paid_formatted: '',
                    tipp: 0,
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
            change() {

                var pays = Number(this.form.pays_formatted.replace(',', '.')),
                    paid = Number(this.form.paid_formatted.replace(',', '.'));

                if (paid == 0) {
                    return 0;
                }

                return (paid - pays);
            },
            net_formatted() {
                return (this.net / 100).format(2, ',', '.')
            },
            paid_formatted() {
                return (this.paid / 100).format(2, ',', '.')
            },
            tipp_formatted() {
                return (this.form.tipp / 100).format(2, ',', '.')
            },
            outstanding_formatted() {
                return (this.outstanding / 100).format(2, ',', '.')
            },
            selected_item() {
                var component = this;

                if (component.form.item_id == 0) {
                    return null;
                }

                return this.options.find(function (item) {
                    return (item.id == component.form.item_id);
                });
            },
            net() {
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

                this.outstanding = (net - this.paid);

                this.form.pays_formatted = (this.outstanding / 100).format(2, ',', '');
                this.gross = gross;

                return net;
            },
        },

        methods: {
            created(item) {
                var component = this;

                var index = -1;
                for (var key in component.items) {
                    if (component.items[key].id == item.id) {
                        index = key;
                    }
                }

                if (index >= 0) {
                    component.updated(index, item);
                }
                else {
                    component.items.unshift(item);
                }
            },
            changedItemId() {
                if (this.selected_item == null) {
                    return;
                }

                if (this.selected_item.is_product) {
                    return;
                }

                this.create();
            },
            deleted(index) {
                var item = this.items[index];
                Vue.delete(this.items, index);
                Vue.successDelete(item);
                this.form.item_id = 0;
            },
            resetForm() {
                // this.form.item_id = 0;
            },
            setTipp() {
                var pays = Number(this.form.pays_formatted.replace(',', '.')) * 100;

                this.form.tipp = (pays - this.outstanding);
            },
            update() {
                var component = this;
                axios.put(component.modelPath, {
                    pays_formatted: component.form.pays_formatted,
                    tipp: component.form.tipp,
                })
                    .then( function (response) {
                        component.errors = {};
                        location.href = response.data.index_path;
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                });
            },
        },
    };
</script>