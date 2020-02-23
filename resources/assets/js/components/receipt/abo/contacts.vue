<template>
    <div class="card mb-3">
        <div class="card-header">Kontakte</div>
        <div class="card-body">
            <div class="form-group">
                <select class="form-control" v-model="contactId" @change="create">
                    <option value="0">Kontakt hinzufügen</option>
                    <option v-for="contact in all" :value="contact.id">{{ contact.name }}</option>
                </select>
            </div>
            <div class="list-group" v-show="selected.length">
                <div class="list-group-item d-flex" v-for="(item, index) in selected" :key="item.id">
                    <div class="col">
                        <a :href="item.path">{{ item.name }}</a>
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

        props: [
            'contacts',
            'showLabel',
            'model'
        ],

        data () {
            return {
                all: this.contacts,
                selected: this.model.contacts,
                label: this.showLabel === undefined ? true : this.showLabel,
                contactId: 0,
                path: '/abos/' + this.model.id,
            }
        },

        beforeMount() {
            // this.fetchAll();
        },

        methods: {
            fetchAll() {
                var component = this;
                axios.get(component.path + '/kontakte')
                    .then( function (response) {
                        component.all = response.data;
                });
            },
            create () {
                var component = this;
                axios.post(component.path + '/kontakte/' + component.contactId)
                    .then(function (response) {
                        component.selected.push(response.data);
                        component.contactId = 0;
                });
            },
            destroy (index) {
                var component = this;
                axios.delete(component.path + '/kontakte/' + component.selected[index].id)
                    .then(function (response) {
                        component.selected.splice(index, 1);
                });
            },
            formatDate(date) {
                return moment.utc(date).utcOffset(120).format('DD.MM.YYYY HH:mm');
            },
        },
    };
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>