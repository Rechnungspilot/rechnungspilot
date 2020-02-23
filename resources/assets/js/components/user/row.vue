<template>
    <tr>
        <td class="align-middle pointer" @click="link">{{ item.name }}</td>
        <td class="align-middle pointer" @click="link">{{ item.tagsString }}</td>
        <td class="text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-secondary" title="Bearbeiten" @click="link"><i class="fas fa-fw fa-edit"></i></button>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy"><i class="fas fa-fw fa-trash"></i></button>
            </div>
        </td>
    </tr>
</template>

<script>
    import moment from "moment";

    export default {

        props: [ 'item', 'uri' ],

        data () {
            return {
                id: this.item.id,
            };
        },

        computed: {

        },

        methods: {
            destroy() {
                axios.delete('/' + this.uri + '/' + this.id);
                this.$emit("deleted", this.id);
            },
            link () {
                location.href = '/' + this.uri + '/' + this.id;
            }
        },
    };
</script>