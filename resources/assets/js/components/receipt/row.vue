<template>

    <show :item="item" :is-selected="isSelected" @destroying="destroy()">

        <template v-slot:show>

            <td class="align-middle">
                <label class="form-checkbox"></label>
                <input :checked="isSelected" type="checkbox" @change="$emit('input', item.id)" number>
            </td>
            <td class="align-middle pointer" @click="show">{{ date }}</td>
            <td class="align-middle pointer" @click="show">
                {{ item.name ? item.name : 'In Vorbereitung' }}
                <div v-html="item.tags_badges"></div>
            </td>
            <td class="align-middle" v-html="item.contact_link_string"></a></td>
            <td class="align-middle text-right pointer" @click="show">{{ (item.net / 100).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2, }) }}</td>
            <td class="align-middle text-right pointer" @click="show">{{ (item.gross / 100).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2, }) }}</td>
            <td class="align-middle pointer" @click="show">{{ item.status ? item.status.name : 'Kein Status' }}</td>

        </template>

    </show>

</template>

<script>
    import moment from "moment";

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

            };
        },

        computed: {
            date() {
                return moment(this.item.date).format('DD.MM.YYYY');
            },
        },

        methods: {

        },
    };
</script>