<template>
    <tr :class="'priority-' + item.priority">
        <th class="align-middle">
            <label class="form-checkbox"></label>
            <input type="checkbox" :checked="isCompleted" @click.prevent="completed">
        </th>
        <td class="align-middle">
            <a class="text-body" :href="item.path">{{ item.name }}</a>
            <div v-if="item.todoable_type == ''"></div>
            <div class="text-muted" v-else-if="item.todoable_type == 'App\\Todos\\Todo'">
                Hauptaufgabe: <a class="text-muted" :href="item.todoable.path">{{ item.todoable.name }}</a>
            </div>
            <div class="text-muted" v-else-if="item.todoable_type == 'App\\Projects\\Section'">
                Projekt: <a class="text-muted" :href="item.todoable.project.path">{{ item.todoable.project.name }} > {{ item.todoable.name }}</a>
            </div>
        </td>
        <td class="align-middle">
            {{ item.user_id > 0 ? item.team.name : 'Nicht zugewiesen' }}
        </td>
        <td class="align-middle">{{ item.tagsString }}</td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-secondary" title="Anzeigen" @click="link(false)"><i class="fas fa-fw fa-eye"></i></button>
                <button class="btn btn-secondary" title="Bearbeiten" @click="link(true)"><i class="fas fa-fw fa-edit"></i></button>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy"><i class="fas fa-fw fa-trash"></i></button>
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
                isCompleted: this.item.completed,
            };
        },

        computed: {
            date() {
                return moment(this.item.date).format('DD.MM.YYYY HH:mm');
            },
        },

        methods: {
            destroy() {
                axios.delete(this.uri + '/' + this.id);
                this.$emit("deleted", this.id);
            },
            completed(event) {
                var component = this,
                    checked = event.target.checked;
                axios.post('/aufgaben/' + component.id + '/erledigt', {
                    completed: checked,
                })
                    .then( function (response) {
                        component.isCompleted = checked;
                    });
            },
            link (edit) {
                location.href = this.item.path + (edit ? '/edit' : '');
            },
        },
    };
</script>