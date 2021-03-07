<template>

    <editable :is-editing="isEditing" @editing="isEditing = $event" @updating="update()" @destroying="destroy()">

        <template v-slot:edit>
            <td class="align-middle pointer">
                <input-text v-model="form.name" placeholder="Name" :error="error('name')" @keydown.enter="update"></input-text>
            </td>
            <td class="align-middle d-none d-sm-table-cell pointer">
                <input-text v-model="form.abbreviation" placeholder="Abkürzung" :error="error('abbreviation')" @keydown.enter="update"></input-text>
            </td>
        </template>

        <template v-slot:show>
            <td class="align-middle pointer" @click="isEditing = true">{{ item.name }}</td>
            <td class="align-middle d-none d-sm-table-cell pointer" @click="isEditing = true">{{ item.abbreviation }}</td>
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
            //
        },

        data () {
            return {
                form: {
                    name: this.item.name,
                    abbreviation: this.item.abbreviation,
                },
            };
        },

        methods: {

        },
    };
</script>