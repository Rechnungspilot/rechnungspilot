<template>
    <div class="card mb-3">
        <div class="card-header">Kontakte</div>
        <div class="card-body">
            <div class="form-group">
                <select class="form-control form-control-sm" v-model="contactId" @change="create">
                    <option value="0">Kontakt hinzufügen</option>
                    <option v-for="contact in available_contacts" :value="contact.id">{{ contact.name }}</option>
                </select>
            </div>
            <table class="table table-fixed table-hover table-striped table-sm bg-white" v-show="selected.length">
                <thead>
                    <tr>
                        <th width="150">Hinzugefügt</th>
                        <th width="100%">Name</th>
                        <th width="30"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in selected">
                        <td>{{ item.pivot.created_at_formatted }}</td>
                        <td>{{ item.name }}</td>
                        <td>
                            <button class="btn btn-link btn-sm pointer py-0" title="Löschen" @click.prevent="destroy(index)"><i class="fas fa-trash text-danger"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import moment from "moment";

    export default {

        props: {
            contacts: {
                required: true,
                type: Array,
            },
            model: {
                required: true,
                type: Object,
            },
            showLabel: {
                required: false,
                type: Boolean,
                default: false,
            },
        },

        data () {
            return {
                selected: this.model.contacts,
                label: this.showLabel === undefined ? true : this.showLabel,
                contactId: 0,
                path: '/abos/' + this.model.id,
            }
        },

        computed: {
            selected_ids() {
                return this.selected.reduce( function (total, contact) {
                    total.push(contact.id);
                    return total;
                }, []);
            },
            available_contacts() {
                var component = this;
                return this.contacts.filter(function (contact) {
                    return (component.selected_ids.indexOf(contact.id) == -1);
                })
            },
        },

        methods: {
            create () {
                var component = this;
                axios.post(component.path + '/kontakte/' + component.contactId)
                    .then(function (response) {
                        component.selected.push(response.data);
                        component.contactId = 0;
                        Vue.success('Kontakt hinzugefügt.');
                });
            },
            destroy (index) {
                var component = this;
                axios.delete(component.path + '/kontakte/' + component.selected[index].id)
                    .then(function (response) {
                        var item = component.selected[index];
                        component.selected.splice(index, 1);
                        Vue.success('Kontakt entfernt.');
                });
            },
            formatDate(date) {
                return moment.utc(date).utcOffset(120).format('DD.MM.YYYY HH:mm');
            },
        },
    };
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>