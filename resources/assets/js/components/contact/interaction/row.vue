<template>
    <tr>
        <td class="align-middle">
            <label class="form-checkbox"></label>
            <input :checked="selected" type="checkbox" :value="id"  @change="$emit('input', id)" number>
        </td>
        <td class="align-middle pointer" @click="link">{{ date }}</td>
        <td class="align-middle pointer" @click="link">
            <div v-if="item.contact">{{ item.contact.name }}</div>
            {{ item.person ? item.person.name : '' }}
        </td>
        <td class="align-middle pointer" @click="link">
            <div class="text-muted">{{ item.type.name }}</div>
            <div>{{ item.name }}</div>
            <div class="text-muted" v-if="item.interactionable_type != null">{{ item.interactionable.name}}</div>
        </td>
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

        props: [ 'model', 'item', 'uri', 'selected' ],

        data () {
            return {
                id: this.item.id,
            };
        },

        computed: {
            date() {
                return moment(this.item.at).format('DD.MM.YYYY HH:mm');
            },
        },

        methods: {
            destroy() {
                var component = this;
                axios.delete('/interaktionen/' + component.id)
                    .then(function (response) {
                        if (response.data.deleted) {
                            component.$emit("deleted", component.id);
                            Vue.success('Interaktion wurde gelöscht.');
                        }
                        else {
                            Vue.error('Interaktion konnte nicht gelöscht werden.');
                        }
                    });
            },
            link () {
                location.href = this.item.path;
            }
        },
    };
</script>