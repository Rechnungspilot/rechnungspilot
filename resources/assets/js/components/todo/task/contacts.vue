<template>
    <div class="card mb-3">
        <div class="card-header">Kontakte</div>
        <div class="card-body">
            <div class="form-group">
                <select class="form-control" v-model="contactId" @change="create">
                    <option value="0">Kontakt hinzufügen</option>
                    <option v-for="contact in selectable" :value="contact.id">{{ contact.name }}</option>
                </select>
            </div>
            <div class="list-group" v-show="selected.length">
                <div class="list-group-item d-flex" v-for="(contact, index) in selected" :key="contact.id">
                    <div class="col">
                        <a :href="contact.path">{{ contact.name }}</a> - <span class="text-muted">hinzugefügt von {{ contact.pivot.user.name }} am {{ formatDate(contact.pivot.created_at) }}</span>
                    </div>
                    <button class="btn btn-link btn-xs pointer py-0" title="Löschen" @click.prevent="destroy(index)"><i class="fas fa-trash text-danger"></i></button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from "moment";

    export default {

        props: {
            model: {
                required: true,
                default: null,
            },
            showLabel: {
                default: false,
                required: false,
            },
        },

        data () {
            return {
                all: {},
                contactId: 0,
                label: this.showLabel === undefined ? true : this.showLabel,
                selected: Object.keys(this.model).length > 0 ? this.model.contacts : [],
                uri: '/aufgaben',
            }
        },

        computed: {
            selectable() {
                var contacts = {}
                for (var index in this.all) {
                    var contact = this.all[index];
                    if (this.selectedIds.indexOf(contact.id) == -1) {
                        contacts[index] = contact;
                    }
                };

                return contacts;
            },
            selectedIds() {
                var ids = [];
                this.selected.forEach(function (item, index) {
                    ids.push(item.id);
                });

                return ids;
            },
        },

        watch: {
            model(newValue) {
                this.selected = newValue ? this.model.contacts : [];
            },
        },

        mounted() {
            this.fetchAll();
        },

        methods: {
            fetchAll() {
                var component = this;
                axios.get('/aufgaben/kontakte')
                    .then( function (response) {
                        component.all = response.data;
                });
            },
            create () {
                var component = this;
                axios.post(component.model.path + '/kontakte/' + component.contactId)
                    .then(function (response) {
                        if (Object.keys(response.data).length === 0) {
                            Vue.error('Kontakt ist schon vorhanden');
                            return;
                        }
                        component.selected.push(response.data);
                        component.contactId = 0;
                        Vue.success('Kontakt hinzugefügt');
                });
            },
            destroy (index) {
                var component = this;
                axios.delete(component.model.path + '/kontakte/' + component.selected[index].id)
                    .then(function (response) {
                        component.selected.splice(index, 1);
                        Vue.success('Kontakt entfernt');
                });
            },
            formatDate(date) {
                return moment(date).format('DD.MM.YYYY HH:mm');
            },
        },
    };
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>