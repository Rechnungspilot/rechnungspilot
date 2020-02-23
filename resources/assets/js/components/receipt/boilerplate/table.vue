<template>
    <div>
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="form-group" style="margin-bottom: 0;">
                    <input type="text" class="form-control" :class="'name' in errors ? 'is-invalid' : ''" v-model="name" placeholder="Name">
                    <div class="invalid-feedback" v-text="'name' in errors ? errors.name[0] : ''"></div>
                </div>&nbsp;
                <div class="form-group" style="margin-bottom: 0;">
                    <button class="btn btn-primary" title="Anlegen" @click="create"><i class="fas fa-plus-square"></i></button>
                </div>
            </div>
            <div class="row">
                <div class="form-group" style="margin-bottom: 0;">
                    <input type="search" class="form-control" v-model="searchtext" @keyup="search" placeholder="suchen..">
                </div>&nbsp;
                <button class="btn btn-outline-primary" @click="showFilter = !showFilter">+ Filter</button>
            </div>
            <form v-if="showFilter" id="filter" style="padding: 15px 0;">

            </form>
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
        <div class="table-responsive" v-else-if="items.length">
            <table class="table table-hover table-striped bg-white">
                <thead>
                    <tr>
                        <th width="20%">Bezeichnung</th>
                        <th width="50%">Text</th>
                        <th width="20%">Standard</th>
                        <th class="text-right" width="10%">Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(item, index) in items">
                        <row :item="item" :key="item.id" :standards="standards" :placeholder="placeholder" :uri="uri" @deleted="remove(index)"></row>
                    </template>
                </tbody>
            </table>
        </div>
        <div class="alert alert-dark" v-else><center>Keine Textbausteine vorhanden</center></div>
    </div>
</template>

<script>
    import row from "./row.vue";

    export default {

        components: {
            row
        },

        props: [
            'placeholder',
            'standards',
        ],

        data () {
            return {
                uri: '/textbausteine',
                items: [],
                isLoading: true,
                showFilter: false,
                searchtext: '',
                searchTimeout: null,
                filter: {

                },
                name: '',
                errors: {},
            };
        },

        mounted() {

            this.fetch();

        },

        methods: {
            create() {
                var component = this;
                axios.post(this.uri, {
                    name: component.name
                })
                    .then(function (response) {
                        component.errors = {};
                        component.name = '';
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