<template>
    <div>
        <div class="row mb-3">
            <div class="col d-flex align-items-start mb-1 mb-sm-0">
                <div class="form-group mb-0 mr-1">
                    <input-text v-model="form.name" placeholder="Name" :error="error('name')" @keydown.enter="create"></input-text>
                </div>
                <div class="form-group mb-0 mr-1">
                    <input-text v-model="form.abbreviation" placeholder="Abkürzung" :error="error('abbreviation')" @keydown.enter="create"></input-text>
                </div>
                <button class="btn btn-primary btn-sm" @click="create"><i class="fas fa-plus-square"></i></button>
            </div>
            <div class="col-auto d-flex">
                <div class="form-group" style="margin-bottom: 0;">
                    <filter-search v-model="filter.searchtext" @input="search"></filter-search>
                </div>
                <button class="btn btn-secondary ml-1" @click="filter.show = !filter.show" v-if="false"><i class="fas fa-filter"></i></button>
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
        <table class="table table-fixed table-hover table-striped table-sm bg-white" v-else-if="items.length">
            <thead>
                <tr>
                    <th width="80%">Bezeichnung</th>
                    <th class="d-none d-sm-table-cell" width="20%">Abkürzung</th>
                    <th class="text-right" width="100">Aktion</th>
                </tr>
            </thead>
            <tbody>
                <row :item="item" :key="item.id" v-for="(item, index) in items" @deleted="deleted(index)" @updated="updated(index, $event)"></row>
            </tbody>
        </table>
        <div class="alert alert-dark" v-else><center>Keine Einheiten vorhanden</center></div>
    </div>
</template>

<script>
    import row from "./row.vue";
    import inputText from '../../form/input/text.vue';
    import filterSearch from "../../filter/search.vue";

    export default {

        components: {
            row,
            inputText,
            filterSearch
        },

        props: {
            indexPath: {
                type: String,
                required: true,
            }
        },

        data () {
            return {
                errors: {},
                filter: {
                    show: false,
                    searchtext: '',
                },
                form: {
                    name: '',
                    abbreviation: '',
                },
                isLoading: true,
                items: [],
            };
        },

        mounted() {

            this.fetch();

        },

        methods: {
            create() {
                var component = this;
                axios.post(this.indexPath, component.form)
                    .then(function (response) {
                        component.resetForm();
                        component.items.unshift(response.data);
                        Vue.successCreate(response.data);
                })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                        Vue.errorCreate();
                });
            },
            resetErrors() {
                this.errors = {};
            },
            resetForm() {
                this.resetErrors();
                for (var index in this.form) {
                    this.form[index] = '';
                }
            },
            error(name) {
                return (name in this.errors ? this.errors[name][0] : '');
            },
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get(component.indexPath, {
                    params: component.filter
                })
                    .then(function (response) {
                        component.items = response.data;
                        component.isLoading = false;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            search () {
                this.fetch();
            },
            deleted(index) {
                var item = this.items[index];
                this.items.splice(index, 1);
                Vue.successDelete(item);
            },
            updated(index, item) {
                Vue.set(this.items, index, item);
                Vue.successUpdate(item);
            },
        },
    };
</script>