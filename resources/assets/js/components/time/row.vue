<template>
    <tr>
        <td class="align-middle text-center">
            <label class="form-checkbox"></label>
            <input :checked="selected" type="checkbox" :value="id"  @change="$emit('input', id)" number>
        </td>
        <td class="align-middle pointer">{{ date }}</td>
        <td class="align-middle pointer">{{ item.user.name }}</td>
        <td class="align-middle">
            <a :href="item.item.path">{{ item.item.name }}</a>
            <div v-if="item.timeable != null">
                <a class="text-muted" :href="item.timeable.path">{{ item.timeable.name }}</a>
            </div>
        </td>
        <td class="align-middle pointer">{{ item.tagsString }}</td>
        <td class="align-middle text-right pointer">{{ item.formatedHours }} h</td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group" v-if="item.end_at == null">
                <button type="button" class="btn btn-secondary" title="Beenden" @click="stop"><i class="fas fa-stop"></i></button>
            </div>
            <div class="btn-group btn-group-sm" role="group" v-else>
                <button type="button" class="btn btn-secondary" title="Abrechnen" @click="invoice"><i class="fas fa-fw fa-file-invoice-dollar"></i></button>
                <button type="button" class="btn btn-secondary" title="Bearbeiten" @click="$emit('edit', item)"><i class="fas fa-fw fa-edit"></i></button>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy"><i class="fas fa-fw fa-trash"></i></button>
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
                return moment(this.item.start_at).format('DD.MM.YYYY HH:mm');
            },
        },

        methods: {
            destroy() {
                axios.delete('/' + this.uri + '/' + this.id);
                this.$emit("deleted", this.id);
            },
            stop () {
                var component = this;
                axios.delete('/zeiterfassung/' + component.id)
                    .then(function (response) {
                        component.$emit('stopped', response.data)
                    })
                    .catch(function (error) {
                        console.log(error);
                });
            },
            invoice() {
                var component = this;
                axios.post(component.item.path + '/abrechnen')
                    .then(function (response) {
                        location.href = response.data.path;
                    })
                    .catch(function (error) {
                        console.log(error);
                        Vue.error('Zeit konnte nicht abgerechnet werden!');
                });
            },
        },
    };
</script>