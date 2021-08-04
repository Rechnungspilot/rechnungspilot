<template>

    <editable :is-editing="isEditing" @editing="isEditing = $event" @updating="update()" @destroying="destroy()">

        <template v-slot:edit>
            <td class="align-middle pointer">
                <input-text v-model="form.unit_value_formatted" :error="error('unit_value_formatted')" @keydown.enter="update" @input="setUnitFormattedPrice()"></input-text>
            </td>
            <td class="align-middle d-none d-sm-table-cell pointer">
                <input-text v-model="form.unit_price_formatted" placeholder="Preis" :error="error('unit_price_formatted')" @keydown.enter="update"></input-text>
            </td>
            <td class="align-middle d-none d-sm-table-cell pointer">{{ item.created_at_date_formatted }}</td>
        </template>

        <template v-slot:show>
            <td class="align-middle pointer" @click="isEditing = true">{{ item.unit_value_formatted }} {{ model.unit.abbreviation }}</td>
            <td class="align-middle d-none d-sm-table-cell pointer" @click="isEditing = true">{{ item.unit_price_formatted }} €</td>
            <td class="align-middle d-none d-sm-table-cell pointer" @click="isEditing = true">{{ item.created_at_date_formatted }}</td>
        </template>

    </editable>

</template>

<script>
    import editable from '../../tables/rows/editable';
    import inputText from '../../form/input/text.vue';

    import { editableMixin } from "../../../mixins/tables/rows/editable.js";

    export default {

        components: {
            editable,
            inputText
        },

        mixins: [
            editableMixin,
        ],

        props: {
            model: {
                required: true,
                type: Object,
            },
        },

        data () {
            return {
                form: {
                    unit_value_formatted: this.item.unit_value_formatted,
                    unit_price_formatted: this.item.unit_price_formatted,
                },
            };
        },

        methods: {
            setUnitFormattedPrice() {
                var unit_value = Number(this.form.unit_value_formatted.replace(',', '.')),
                    unit_price = unit_value * this.model.unit_price;

                this.form.unit_price_formatted = unit_price.format(this.model.decimals, ',', '');
            }
        },
    };
</script>