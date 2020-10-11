<template>
    <tr>
        <td class="align-middle text-center">
            <label class="form-checkbox"></label>
            <input :checked="selected" type="checkbox" :value="id"  @change="$emit('input', id)" number>
        </td>
        <td class="align-middle pointer" @click="link">{{ date }}</td>
        <td class="align-middle pointer" @click="link">
            {{ item.name ? item.name : 'In Vorbereitung' }}
            <div v-html="item.tags_badges"></div>
        </td>
        <td class="align-middle" v-html="item.contact_link_string"></a></td>
        <td class="align-middle text-right pointer" @click="link">{{ (item.net / 100).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2, }) }}</td>
        <td class="align-middle text-right pointer" @click="link">{{ (item.gross / 100).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2, }) }}</td>
        <td class="align-middle pointer" @click="link">{{ item.status.name }}</td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-secondary" title="Bearbeiten" @click="link"><i class="fas fa-edit"></i></button>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy"><i class="fas fa-trash"></i></button>
            </div>
        </td>
    </tr>
</template>

<script>
    import moment from "moment";

    export default {

        props: [ 'item', 'uri', 'selected' ],

        data () {
            return {
                id: this.item.id,
            };
        },

        computed: {
            date() {
                return moment(this.item.date).format('DD.MM.YYYY');
            },
        },

        methods: {
            destroy() {
                var component = this;
                axios.delete(component.item.path.replace('/edit', ''))
                    .then(function (response) {
                        component.$emit("deleted", component.id);
                    });
            },
            link () {
                location.href = this.item.path;
            }
        },
    };
</script>