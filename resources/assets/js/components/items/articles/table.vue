<template>

    <div>

        <div v-if="tag_item != null">
            Etikett (TODO): <i class="fas fa-tag"></i>
        </div>

        <table-base :is-loading="isLoading" :items-length="items.length" :is-showing-footer="true" :has-filter="hasFilter()" @searching="searching($event)" @creating="create">

            <template v-slot:form>
                <div class="form-group mb-0 mr-1">
                    <input-text v-model="form.unit_value_formatted" :placeholder="model.unit.name + ' (' + model.unit.abbreviation + ')'" :error="error('unit_value_formatted')" @keydown.enter="create" @input="setUnitFormattedPrice()"></input-text>
                </div>
                <div class="form-group mb-0 mr-1">
                    <input-text v-model="form.unit_price_formatted" placeholder="Preis" :error="error('unit_price_formatted')"></input-text>
                </div>
            </template>

            <template v-slot:thead>
                <tr>
                    <th width="50%">{{ model.unit.name }} ({{ model.unit.abbreviation }})</th>
                    <th class="d-none d-sm-table-cell" width="50%">Preis</th>
                    <th class="d-none d-sm-table-cell" width="100">Erstellt</th>
                    <th class="text-right" width="100">Aktion</th>
                </tr>
            </template>

            <template v-slot:tbody>
                <row :item="item" :model="model" :key="item.id" v-for="(item, index) in items" @deleted="deleted(index)" @updated="updated(index, $event)"></row>
            </template>

            <template v-slot:tfoot>
                <tr class="font-weight-bold">
                    <td class="">Ø {{ Number(sums.unit_value / items.length).format(2, ',', '.') }}</td>
                    <td class="d-none d-sm-table-cell">Ø {{ Number(sums.unit_price / items.length).format(2, ',', '.') }}</td>
                    <td class="d-none d-sm-table-cell"></td>
                    <td></td>
                </tr>
            </template>

        </table-base>

    </div>


</template>

<script>
    import row from './row.vue';
    import inputText from '../../form/input/text.vue';
    import tableBase from '../../tables/base.vue';

    import { baseMixin } from "../../../mixins/tables/base.js";
    import { paginatedMixin } from "../../../mixins/tables/paginated.js";

    export default {

        components: {
            inputText,
            row,
            tableBase,
        },

        props: {
            model: {
                required: true,
                type: Object,
            },
            createdAtDate: {
                required: false,
                type: String,
                default: null,
            }
        },

        mixins: [
            baseMixin,
            paginatedMixin,
        ],

        computed: {
            sums() {
                var sums = {
                    unit_value: 0,
                    unit_price: 0,
                };

                this.items.forEach(function (article, index) {
                    sums.unit_value += Number(article.unit_value);
                    sums.unit_price += Number(article.unit_price);
                });

                return sums;
            }
        },

        data () {
            return {
                filter: {
                    created_at_date: this.createdAtDate,
                },
                form: {
                    unit_value_formatted: '',
                    unit_price_formatted: '',
                },
                tag_item: null,
            };
        },

        methods: {
            created(item) {
                this.items.unshift(item);
                // this.tag_item = item;
            },
            setUnitFormattedPrice() {
                var unit_value = Number(this.form.unit_value_formatted.replace(',', '.')),
                    unit_price = unit_value * this.model.unit_price;

                this.form.unit_price_formatted = unit_price.format(this.model.decimals, ',', '');
            },
        },

    };
</script>