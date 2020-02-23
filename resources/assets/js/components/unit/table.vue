<template>
    <div>
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="form-group" style="margin-bottom: 0;">
                    <input type="text" class="form-control" :class="'name' in errors ? 'is-invalid' : ''" v-model="name" placeholder="Name" @keydown.enter="create">
                    <div class="invalid-feedback" v-text="'name' in errors ? errors.name[0] : ''"></div>
                </div>&nbsp;
                <div class="form-group" style="margin-bottom: 0;">
                    <input type="text" class="form-control" :class="'abbreviation' in errors ? 'is-invalid' : ''" v-model="abbreviation" placeholder="Abkürzung" @keydown.enter="create">
                    <div class="invalid-feedback" v-text="'abbreviation' in errors ? errors.abbreviation[0] : ''"></div>
                </div>&nbsp;
                <div class="form-group" style="margin-bottom: 0;">
                    <button class="btn btn-primary" title="Anlegen" @click="create"><i class="fas fa-plus-square"></i></button>
                </div>
            </div>
            <div class="row">
                <div class="form-group" style="margin-bottom: 0;">
                    <input type="search" class="form-control" v-model="searchtext" @keyup="search" placeholder="suchen..">
                </div>&nbsp;
            </div>
        </div>
        <br />
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
                    <th width="40%">Bezeichnung</th>
                    <th width="50%">Abkürzung</th>
                    <th class="text-right" width="10%">Aktion</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(item, index) in items">
                    <row :item="item" :uri="uri" :key="item.id" @deleted="remove(index)"></row>
                </template>
            </tbody>
        </table>
        <div class="alert alert-dark" v-else><center>Keine items vorhanden</center></div>
    </div>
</template>

<script>
    import Row from "./row.vue";

    export default {

        components: { Row },

        props: {

        },

        data () {
            return {
                uri: '/einheiten',
                items: [],
                isLoading: true,
                showFilter: false,
                searchtext: '',
                searchTimeout: null,
                filter: {

                },
                name: '',
                abbreviation: '',
                errors: {},
            };
        },

        mounted() {

            this.fetch();

        },

        watch: {
            page () {
                this.fetch();
            },
        },

        methods: {
            create() {
                var component = this;
                axios.post(this.uri, {
                    name: component.name,
                    abbreviation: component.abbreviation
                })
                    .then(function (response) {
                        component.errors = {};
                        component.name = '';
                        component.abbreviation = '';
                        component.items.unshift(response.data);
                })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                });
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