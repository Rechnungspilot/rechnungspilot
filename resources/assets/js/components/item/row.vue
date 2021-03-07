<template>

    <show :item="item" :is-selected="isSelected" @destroying="destroy()">

        <template v-slot:show>

            <td class="align-middle">
                <label class="form-checkbox"></label>
                <input :checked="isSelected" type="checkbox" @change="$emit('input', item.id)" number>
            </td>
            <td class="align-middle text-right pointer" @click="show">{{ item.number }}</td>
            <td class="align-middle pointer" @click="show">
                {{ item.name }}
                <div v-html="item.tags_badges"></div>
            </td>
            <td class="align-middle pointer text-right" @click="show">{{ (parseFloat(item.unit_price) * (1 + parseFloat(item.tax))).format(2, ',', '.') }} €</td>
            <td class="align-middle pointer text-right" @click="show">{{ parseFloat(item.unit_price).format(2, ',', '.') }} €</td>
            <td class="align-middle pointer" @click="show">{{ item.unit.abbreviation }}</td>
            <td class="align-middle pointer" @click="show">{{ (item.tax * 100).format(1, ',', '.') }} %</td>
            <td class="align-middle pointer text-right" @click="show">{{ (item.revenue / 100).format(2, ',', '.') }} €</td>

        </template>

    </show>

</template>

<script>
    import show from '../tables/rows/show';

    export default {

        components: {
            show,
        },

        props: {
            item: {
                type: Object,
                required: true,
            },
            isSelected: {
                type: Boolean,
                required: false,
                default: false,
            },
        },

        data () {
            return {
                //
            };
        },

        methods: {
            destroy() {
                axios.delete(this.item.path);
                this.$emit("deleted", this.id);
            },
            edit() {
                location.href = this.item.path + '/edit';
            },
            show() {
                location.href = this.item.path;
            }
        },
    };
</script>