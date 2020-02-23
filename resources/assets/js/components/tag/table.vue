<template>
    <div>
        <div class="row">
            <div class="col d-flex">

                <div class="form-group mr-1" style="margin-bottom: 0;">
                    <input type="text" class="form-control" :class="'name' in errors ? 'is-invalid' : ''" v-model="form.name" placeholder="Tag Name" @keydown.enter="create">
                    <div class="invalid-feedback" v-text="'name' in errors ? errors.name[0] : ''"></div>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <button class="btn btn-primary" title="Anlegen" @click="create"><i class="fas fa-plus-square"></i></button>
                </div>

            </div>

            <div class="col-auto d-flex">
                <div class="form-group" style="margin-bottom: 0;">
                    <filter-search v-model="filter.searchtext" @input="fetch()"></filter-search>
                </div>
                <button class="btn btn-secondary ml-1" @click="filter.show = !filter.show"><i class="fas fa-filter"></i></button>
            </div>

        </div>

        <form v-if="filter.show" id="filter" class="mt-1">
            <div  class="form-row">



            </div>
        </form>

        <div v-if="isLoading" class="mt-3 p-5">
            <center>
                <span style="font-size: 48px;">
                    <i class="fas fa-spinner fa-spin"></i><br />
                </span>
                Lade Daten..
            </center>
        </div>
        <table class="table table-hover table-striped bg-white mt-3" v-else-if="tags.length">
            <thead>
                <tr>
                    <th width="90%">Bezeichnung</th>
                    <th class="text-right" width="10%">Aktion</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(tag, index) in tags">
                    <row :tag="tag" :key="tag.id" @deleted="remove(index)"></row>
                </template>
            </tbody>
        </table>
        <div class="alert alert-dark mt-3" v-else><center>Keine Tags vorhanden</center></div>
    </div>
</template>

<script>
    import Row from "./row.vue";
    import filterSearch from "../filter/search.vue";

    export default {

        components: {
            filterSearch,
            Row,
        },

        props: {
            slug: {
                type: String,
                required: true,
            },
        },

        data () {
            return {
                tags: [],
                isLoading: true,
                showFilter: false,
                searchtext: '',
                searchTimeout: null,
                filter: {
                    searchtext: '',
                },
                form: {
                    name: '',
                    type: this.slug,
                },
                errors: {},
                uri: '/kategorien/' + this.slug,
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
                axios.post(component.uri, component.form)
                    .then(function (response) {
                        component.errors = {};
                        component.form.name = '';
                        component.tags.unshift(response.data);
                })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                });
            },
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get(component.uri, {
                    params: component.filter,
                })
                    .then(function (response) {
                        component.tags = response.data;
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
                this.tags.splice(index, 1);
            },
        },
    };
</script>