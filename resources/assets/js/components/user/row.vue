<template>

    <show :item="item" :is-selected="isSelected" @destroying="destroy()">

        <template v-slot:show>

            <td class="align-middle">
                <label class="form-checkbox"></label>
                <input :checked="isSelected" type="checkbox" @change="$emit('input', item.id)" number>
            </td>
            <td class="align-middle pointer" @click="show">{{ item.name }}</td>
            <td class="align-middle pointer" @click="show">{{ item.tagsString }}</td>

        </template>

    </show>

</template>

<script>
    import show from '../tables/rows/show.vue';

    import { showMixin } from "../../mixins/tables/rows/show.js";

    export default {

        components: {
            show,
        },

        mixins: [
            showMixin,
        ],

        props: {
            //
        },

        data () {
            return {
                //
            };
        },

        methods: {

        },

    };
</script>