<template>
    <div>
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="form-group" style="margin-bottom: 0;">
                    <button class="btn btn-primary" title="Anlegen" @click.prevent="create"><i class="fas fa-plus-square"></i></button>
                </div>
            </div>
        </div>
        <div v-if="isLoading" class="p-5">
            <center>
                <span style="font-size: 48px;">
                    <i class="fas fa-spinner fa-spin"></i><br />
                </span>
                Lade Daten..
            </center>
        </div>
        <table class="table table-hover table-striped bg-white" v-else-if="items.length">
            <thead>
                <tr>
                    <th width="5%"></th>
                    <th width="15%">Datum</th>
                    <th width="20%">Bezeichnung</th>
                    <th width="20%">Mitarbeiter</th>
                    <th width="30%">Zeit</th>
                    <th class="text-right" width="10%">Aktion</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(item, index) in items">
                    <row :item="item" :key="item.id" :uri="uri" @deleted="remove(index)" @updating="updating(index, $event)"></row>
                </template>
            </tbody>
        </table>
        <div class="alert alert-dark" v-else><center>Keine Termine vorhanden</center></div>
        <create :initial-start-at="createAttributes.start_at" :initial-contact="initialContact" :receipt-id="model.id" :users="users" @created="created"></create>
        <edit :todo="editAttributes.item" :users="users" @updated="updated" @deleted="remove(editAttributes.index)"></edit>
    </div>
</template>

<script>
    import create from '../../calendar/todo/create.vue';
    import edit from '../../calendar/todo/edit.vue';
    import row from "./row.vue";

    export default {

        components: {
            create,
            edit,
            row,
        },

        props: {
            model: {
                type: Object,
                required: true,
            },
            users: {
                type: Array,
                required: true,
            },
            type: {
                type: String,
                default: 'belege',
            },
            initialContact: {
                type: Object,
                default: null,
            }
        },

        data () {
            return {
                uri: '/' + this.type + '/' + this.model.id + '/aufgaben',
                items: [],
                isLoading: true,
                showFilter: false,
                searchtext: '',
                searchTimeout: null,
                filter: {

                },
                errors: {},
                createAttributes: {
                    start_at: null,
                },
                editAttributes: {
                    index: 0,
                    item: {},
                },
            };
        },

        mounted() {

            this.fetch();

        },

        methods: {
            create() {
                this.createAttributes.start_at = (new Date()).toISOString();
                $('#todo-create').modal('show');
            },
            created(todo) {
                this.items.unshift(todo);
            },
            updating(index, todo) {
                this.editAttributes.index = index;
                this.editAttributes.item = todo;
                $('#todo-edit').modal('show');
            },
            updated(todo) {
                Vue.set(this.items, this.editAttributes.index, todo);
            },
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get(this.uri + '?searchtext=' + component.searchtext)
                    .then(function (response) {
                        component.items = response.data;
                        component.isLoading = false;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            search () {
                var component = this;
                if (component.searchTimeout)
                {
                    clearTimeout(component.searchTimeout);
                    component.searchTimeout = null;
                }
                component.searchTimeout = setTimeout(function () {
                    component.fetch()
                }, 300);
            },
            remove(index) {
                this.items.splice(index, 1);
            },
        },
    };
</script>