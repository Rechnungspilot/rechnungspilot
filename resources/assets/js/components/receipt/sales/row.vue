<template>

    <show :item="item" :is-selected="isSelected" @destroying="destroy()">

        <template v-slot:show>

            <td class="align-middle pointer" @click="show">{{ date }}</td>
            <td class="align-middle pointer" @click="show">{{ item.contact.name }}</td>
            <td class="align-middle text-right pointer" @click="show">{{ (item.net / 100).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2, }) }}</td>

        </template>

    </show>

</template>

<script>
    import moment from "moment";

    import show from '../../tables/rows/show.vue';

    import { showMixin } from '../../../mixins/tables/rows/show.js';

    export default {

        components: {
            show,
        },

        mixins: [
            showMixin,
        ],

        props: {
            indexPath: {
                required: true,
                type: String,
            },
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
            show() {
                location.href = this.indexPath + '/' + this.item.id;
            },
        },
    };
</script>