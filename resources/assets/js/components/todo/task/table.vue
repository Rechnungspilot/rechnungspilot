<template>
    <div>
        <div class="container-fluid" v-if="hideFilter == undefined">
            <div class="row mb-3">
                <div class="form-group" style="margin-bottom: 0;">
                    <button class="btn btn-primary" title="Anlegen" @click.prevent="create"><i class="fas fa-plus-square"></i></button>
                </div>
            </div>
            <div class="row mb-3">
                <div class="form-group" style="margin-bottom: 0;">
                    <input type="search" class="form-control" v-model="filter.searchtext" @keyup="search" placeholder="suchen..">
                </div>&nbsp;
                <button class="btn btn-outline-primary" @click="showFilter = !showFilter">+ Filter</button>
            </div>
            <form v-if="showFilter" id="filter" style="padding: 15px 0;">
                <div  class="form-row">
                    <filter-completed v-model="filter.completed" @input="fetch"></filter-completed>
                    <filter-team :options="filterTeam" v-model="filter.team" @input="fetch"></filter-team>
                    <filter-tags :options="filterTags" v-model="filter.tags" @input="fetch"></filter-tags>
                    <filter-per-page v-model="filter.perPage" @input="fetch"></filter-per-page>
                </div>
            </form>
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
                    <th width="30"></th>
                    <th width="50%">Bezeichnung</th>
                    <th width="50%" v-if="options.show_team">Mitarbeiter</th>
                    <th class="text-right" width="115">Aktion</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(item, index) in items">
                    <row :item="item" :key="item.id" :uri="uri" :options="options" @deleted="remove(index)"></row>
                </template>
            </tbody>
        </table>
        <div class="alert alert-dark" v-else><center>Keine Aufgaben vorhanden</center></div>
        <nav aria-label="Page navigation example">
            <ul class="pagination" v-show="paginate.lastPage > 1">
                <li class="page-item" v-show="paginate.prevPageUrl">
                    <a class="page-link" href="#" @click.prevent="page--">Previous</a>
                </li>

                <li class="page-item" v-for="n in paginate.lastPage" v-bind:class="{ active: (n == page) }"><a class="page-link" href="#" @click.prevent="page = n">{{ n }}</a></li>

                <li class="page-item" v-show="paginate.nextPageUrl">
                    <a class="page-link" href="#" @click.prevent="page++">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
    import row from "./row.vue";
    import filterCompleted from "../../filter/completed.vue";
    import filterTags from "../../filter/tags.vue";
    import filterTeam from "../../filter/team.vue";
    import filterPerPage from "../../filter/perPage.vue";

    export default {

        components: {
            row,
            filterCompleted,
            filterTags,
            filterTeam,
            filterPerPage,
        },

        props: {
            filterTags: {
                required: false,
                type: Array,
                default: function () { return []; },
            },
            filterTeam: {
                required: false,
                type: Array,
                default: function () { return []; },
            },
            contactId: {
                required: false,
                type: Number,
                default: 0,
            },
            hideFilter: {
                required: false,
                type: String,
                default: '0',
            },
            teamId: {
                required: false,
                type: Number,
                default: 0,
            },
        },

        data () {
            return {
                uri: '/aufgaben',
                items: [],
                isLoading: true,
                showFilter: true,
                searchTimeout: null,
                page: 1,
                paginate: {
                    nextPageUrl: null,
                    prevPageUrl: null,
                    lastPage: 0,
                },
                options: {
                    show_team: (this.teamId == 0),
                },
                filter: {
                    completed: -1,
                    contact_id: this.contactId == undefined ? 0 : this.contactId,
                    perPage: 25,
                    searchtext: '',
                    tags: [],
                    team: this.teamId || 0,
                },
                errors: {},
            };
        },

        mounted() {

            this.fetch();

        },

        methods: {
            create() {
                var component = this;
                axios.post(this.uri)
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                    })
                    .then(function (response) {
                        component.errors = {};
                        component.items.unshift(response.data);
                });
            },
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get(this.uri, {
                    params: component.filter
                })
                    .then(function (response) {
                        component.items = response.data.data;
                        component.page = response.data.current_page;
                        component.paginate.nextPageUrl = response.data.next_page_url;
                        component.paginate.prevPageUrl = response.data.prev_page_url;
                        component.paginate.lastPage = response.data.last_page;
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
                Vue.success('Aufgabe gelöscht');
                this.items.splice(index, 1);
            },
        },
    };
</script>