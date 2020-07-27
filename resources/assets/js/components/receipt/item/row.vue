<template>
    <tr v-if="edit">
        <td></td>
        <td class="align-middle pointer">
            <input class="form-control mb-1" :class="'name' in errors ? 'is-invalid' : ''" type="text" v-model="form.name">
            <div class="invalid-feedback" v-text="'name' in errors ? errors.name[0] : ''"></div>
            <textarea class="form-control" :class="'description' in errors ? 'is-invalid' : ''" v-model="form.description" rows="3"></textarea>
            <div class="invalid-feedback" v-text="'description' in errors ? errors.description[0] : ''"></div>
        </td>
        <td class="align-middle pointer">
            <number-input v-model="form.quantity" :error="'quantity' in errors ? errors.quantity[0] : ''"></number-input>
        </td>
        <td class="align-middle pointer">
            <select class="form-control" :class="'unit_id' in errors ? 'is-invalid' : ''" v-model="form.unit_id">
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
            <select class="form-control" :class="'tax' in errors ? 'is-invalid' : ''" v-model="form.tax">
                <option :value="key" v-for="(label, key) in taxes">{{ label }}</option>
            </select>
            <div class="invalid-feedback" v-text="'tax' in errors ? errors.tax[0] : ''"></div>
        </td>
        <td class="align-middle text-right">{{ net }}</td>
        <td></td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button class="btn btn-secondary" title="Speichern" @click="update"><i class="fas fa-save"></i></button>
                <button type="button" class="btn btn-secondary" title="Abbrechen" @click="edit = false"><i class="fas fa-times"></i></button>
            </div>
        </td>
    </tr>
    <tr v-else>
        <td class="align-middle">
            <label class="form-checkbox"></label>
            <input :checked="selected" type="checkbox" :value="id"  @change="$emit('input', id)" number>
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
        <td class="align-middle pointer text-right">
            <div class="btn-group btn-group-sm" role="group">
                <a :href="'/artikel/' + item.item_id" target="_blank" class="btn btn-secondary" title="Artikel" v-if="item.item_id > 0"><i class="fas fa-fw fa-eye"></i></a>
                <button class="btn btn-secondary" title="Bearbeiten" @click="edit = true"><i class="fas fa-edit"></i></button>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy"><i class="fas fa-trash"></i></button>
            </div>
        </td>
    </tr>
</template>

<script>
    import currencyInput from '../../form/input/currency.vue';
    import numberInput from '../../form/input/number.vue';

    export default {

        components: {
            currencyInput,
            numberInput,
        },

        props: [
            'item',
            'selected',
            'units',
            'company'
        ],

        data () {
            return {
                id: this.item.id,
                edit: this.item.shouldEdit || false,
                form: {
                    description: this.item.description,
                    discount: Number(this.item.discount) * 100,
                    name: this.item.name,
                    quantity: Number(this.item.quantity),
                    tax: this.item.tax,
                    unit_id: this.item.unit_id,
                    unit_price: Number(this.item.unit_price),
                },
                errors: {},
                uri: '/belege/' + this.item.receipt_id + '/artikel/' + this.item.id,
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
            destroy() {
                var component = this;
                axios.delete(this.uri)
                    .then( function (response) {
                        component.$emit("deleted", component.id);
                    })
                    .catch(function (error) {
                        console.log(error);
                });
            },
            update() {
                var component = this;
                axios.put(this.uri, this.form)
                    .then( function (response) {
                        component.errors = {};
                        component.edit = false;
                        component.$emit('updated', response.data)
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                });
            },
        },
    };
</script>