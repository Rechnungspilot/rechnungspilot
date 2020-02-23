<template>
    <tr>
        <td class="align-middle">
            <label class="form-checkbox"></label>
            <input :checked="selected" type="checkbox" :value="id"  @change="$emit('input', id)" number>
        </td>
        <td class="align-middle pointer" @click="link">{{ date }}</td>
        <td class="align-middle pointer"><a :href="item.invoice.path">{{ item.invoice.name }}</a></td>
        <td class="align-middle pointer" @click="link">{{ item.settings.level.name }}</td>
        <td class="align-middle"><a :href="'/kontakte/' + item.invoice.contact.id">{{ item.invoice.contact.name }}</a></td>
        <td class="align-middle text-right pointer" @click="link">{{ (item.gross / 100).format(2, ',', '.') }}</td>
        <td class="align-middle text-right pointer" @click="link">{{ (item.outstanding / 100).format(2, ',', '.') }}</td>
        <td class="text-right">
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
                axios.delete(this.uri + '/' + this.id);
                this.$emit("deleted", this.id);
            },
            link () {
                location.href = this.item.path + '/edit';
            }
        },
    };
</script>