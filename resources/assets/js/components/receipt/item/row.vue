<template>

    <editable :is-editing="isEditing" @editing="isEditing = $event" @updating="update()" @destroying="destroy()">

        <template v-slot:edit>
            <td></td>
            <td class="align-middle pointer">
                <input class="form-control form-control-sm mb-1" :class="'name' in errors ? 'is-invalid' : ''" type="text" v-model="form.name">
                <div class="invalid-feedback" v-text="'name' in errors ? errors.name[0] : ''"></div>
                <textarea class="form-control form-control-sm" :class="'description' in errors ? 'is-invalid' : ''" v-model="form.description" rows="3"></textarea>
                <div class="invalid-feedback" v-text="'description' in errors ? errors.description[0] : ''"></div>
            </td>
            <td class="align-middle pointer">
                <number-input v-model="form.quantity" :error="'quantity' in errors ? errors.quantity[0] : ''"></number-input>
            </td>
            <td class="align-middle pointer">
                <select class="form-control form-control-sm" :class="'unit_id' in errors ? 'is-invalid' : ''" v-model="form.unit_id">
                    <option :value="unit.id" v-for="unit in units">{{ unit.name }}</option>
                </select>
                <div class="invalid-feedback" v-text="'unit_id' in errors ? errors.unit_id[0] : ''"></div>
            </td>
            <td class="align-middle pointer">
                <currency-input v-model="form.unit_price" :error="'unit_price' in errors ? errors.unit_price[0] : ''" :decimals="item.item.decimals"></currency-input>
            </td>
            <td class="align-middle pointer">
                <number-input v-model="form.discount" :error="'discount' in errors ? errors.discount[0] : ''"></number-input>
            </td>
            <td class="align-middle pointer">
                <select class="form-control form-control-sm" :class="'tax' in errors ? 'is-invalid' : ''" v-model="form.tax">
                    <option :value="key" v-for="(label, key) in taxes">{{ label }}</option>
                </select>
                <div class="invalid-feedback" v-text="'tax' in errors ? errors.tax[0] : ''"></div>
            </td>
            <td class="align-middle text-right">{{ net }}</td>
            <td></td>
        </template>

        <template v-slot:show>
            <td class="align-middle">
                <label class="form-checkbox"></label>
                <input :checked="isSelected" type="checkbox" @change="$emit('input', item.id)" number>
            </td>
            <td class="align-middle pointer" @click="edit = true">
                {{ item.name }}
                <div class="text-muted whitespace-pre" v-show="item.description" v-html="item.description"></div>
            </td>
            <td class="align-middle pointer text-right" @click="edit = true">{{ item.quantity.format(2, ',', '.') }}</td>
            <td class="align-middle pointer" @click="edit = true">{{ item.unit ? item.unit.name : '' }}</td>
            <td class="align-middle pointer text-right" @click="edit = true">{{ item.unit_price.format(item.item.decimals, ',', '.') }} €</td>
            <td class="align-middle pointer text-right" @click="edit = true">{{ (item.discount * 100).format(1, ',', '') }}%</td>
            <td class="align-middle pointer text-right" @click="edit = true" v-if="company.sales_tax">{{ (item.tax * 100).format(0, ',', '') }}%</td>
            <td class="align-middle pointer text-right" @click="edit = true">{{ (item.net / 100).format(2, ',', '.') }} €</td>
            <td class="align-middle text-center">
                <a :href="item.morphed_items[0].receipt.path" :title="item.morphed_items[0].receipt.typeName + ' ' + item.morphed_items[0].receipt.name" target="_blank" v-if="item.morphed_items.length > 0"><i class="fas fa-file-invoice"></i></a>
            </td>

        </template>

        <template v-slot:preBtnGroup>
            <a :href="item.item.path" target="_blank" class="btn btn-secondary btn-sm" title="Artikel" v-if="item.item_id > 0"><i class="fas fa-fw fa-eye"></i></a>
        </template>

    </editable>

</template>

<script>
    import currencyInput from '../../form/input/currency.vue';
    import numberInput from '../../form/input/number.vue';
    import editable from '../../tables/rows/editable';

    import { editableMixin } from "../../../mixins/tables/rows/editable.js";

    export default {

        components: {
            currencyInput,
            editable,
            numberInput,
        },

        mixins: [
            editableMixin,
        ],

        props: {
            company: {
                type: Object,
                required: true,
            },
            units: {
                type: Array,
                required: true,
            },
        },

        data () {
            return {
                isEditing: this.item.should_edit || false,
                form: {
                    description: this.item.description,
                    discount: Number(this.item.discount) * 100,
                    name: this.item.name,
                    quantity: Number(this.item.quantity),
                    tax: this.item.tax,
                    unit_id: this.item.unit_id,
                    unit_price: Number(this.item.unit_price),
                },
                taxes: {
                    '0.190': '19%',
                    '0.160': '16%',
                    '0.070': '7%',
                    '0.000': '0%',
                },
            };
        },

        computed: {
            net() {
                return (this.form.quantity * this.form.unit_price * (1 - (this.form.discount / 100))).format(2, ',', '.');
            },
        },

        methods: {
            //
        },
    };
</script>