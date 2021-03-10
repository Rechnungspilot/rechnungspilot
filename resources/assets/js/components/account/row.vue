<template>

    <editable :is-editing="isEditing" @editing="isEditing = $event" @updating="update()" @destroying="destroy()">

        <template v-slot:edit>
            <td class="align-middle pointer">
                <input-text v-model="form.name" placeholder="Name" :error="error('name')" @keydown.enter="update"></input-text>
            </td>
            <td class="align-middle text-right pointer" @click="isEditing = true">{{ item.bank ? (item.amount / 100).format(2, ',', '.') + ' €' : '-' }}</td>
            <td class="align-middle text-right pointer">{{ item.bank ? item.bank.last_import_at_formatted : '' }}</td>
        </template>

        <template v-slot:show>
            <td class="align-middle pointer" @click="isEditing = true">
                {{ item.name }}
                <div class="text-muted" v-if="item.bank_company_id > 0">{{ item.bank.bank.name }}</div>
            </td>
            <td class="align-middle text-right pointer" @click="isEditing = true">{{ item.bank ? (item.amount / 100).format(2, ',', '.') + ' €' : '-' }}</td>
            <td class="align-middle text-right pointer">{{ item.bank ? item.bank.last_import_at_formatted : '' }}</td>
        </template>

    </editable>

</template>

<script>
    import editable from '../tables/rows/editable';
    import inputText from '../form/input/text.vue';

    import { editableMixin } from "../../mixins/tables/rows/editable.js";

    export default {

        components: {
            editable,
            inputText
        },

        mixins: [
            editableMixin,
        ],

        data () {
            return {
                form: {
                    name: this.item.name
                }
            };
        },

        methods: {
            //
        },
    };
</script>