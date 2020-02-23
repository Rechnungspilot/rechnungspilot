<template>
    <tr>
        <th class="align-middle">
            <label class="form-checkbox"></label>
            <input type="checkbox" :checked="isCompleted" @click.prevent="completed">
        </th>
        <td class="align-middle pointer">{{ date }}</td>
        <td class="align-middle pointer">{{ item.name }}</td>
        <td class="align-middle pointer">{{ item.team ? item.team.name : 'Nicht zugewiesen' }}</td>
        <td class="align-middle pointer" v-if="item.times">
        <div v-for="time in item.times">
            {{ time.item.name }} {{ time.end_at == null ? 'läuft..' : time.formatedHours + ' h' }} ({{ time.user.name }})
        </div>
        </td>
        <td class="align-middle pointer" v-else>-</td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button class="btn btn-secondary" title="Bearbeiten" @click.prevent="$emit('updating', item)"><i class="fas fa-edit"></i></button>
                <button type="button" class="btn btn-secondary" title="Löschen" @click.prevent="destroy"><i class="fas fa-trash"></i></button>
            </div>
        </td>
    </tr>
</template>

<script>
    import moment from "moment";

    export default {

        props: [
            'item',
            'uri',
        ],

        data () {
            return {
                id: this.item.id,
                link: this.uri + '/' + this.item.id + '/edit',
                isCompleted: this.item.completed,
            };
        },

        computed: {
            date() {
                return this.item.start_at ? moment(this.item.start_at).format('DD.MM.YYYY HH:mm') : '-';
            },
        },

        methods: {
            destroy() {
                axios.delete(this.item.path);
                this.$emit("deleted", this.id);
            },
            completed(event) {
                var component = this,
                    checked = event.target.checked;

                (checked ? this.complete() : this.incomplete()).then( function (response) {
                    component.isCompleted = checked;
                    Vue.success('Aufgabe ist ' + (checked ? 'erledigt' : 'unerledigt'));
                });
            },
            complete() {
                return axios.post('/aufgaben/' + this.id + '/erledigt');
            },
            incomplete() {
                return axios.delete('/aufgaben/' + this.id + '/erledigt');
            },
        },
    };
</script>