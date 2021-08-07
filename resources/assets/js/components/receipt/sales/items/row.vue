<template>

    <show :item="item" :has-edit-button="false" :has-show-button="false" @destroying="destroy()">

        <template v-slot:show>
            <td class="align-middle pointer" @click="edit = true">{{ item.name }}</td>
            <td class="align-middle pointer text-right" @click="edit = true">{{ item.quantity.format(2, ',', '.') }}</td>
            <td class="align-middle pointer" @click="edit = true">{{ item.unit ? item.unit.abbreviation : '' }}</td>
            <td class="align-middle pointer text-right" @click="edit = true">{{ (item.net / 100).format(2, ',', '.') }} €</td>
        </template>

    </show>

</template>

<script>
    import currencyInput from '../../../form/input/currency.vue';
    import numberInput from '../../../form/input/number.vue';
    import show from '../../../tables/rows/show';

    import { showMixin } from "../../../../mixins/tables/rows/show.js";

    export default {

        components: {
            currencyInput,
            show,
            numberInput,
        },

        mixins: [
            showMixin,
        ],

        props: {
            company: {
                type: Object,
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
                    '0.107': '10,7%',
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