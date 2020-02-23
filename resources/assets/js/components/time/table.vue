<template>
    <div>
        <div class="row">
            <div class="col">
                <button class="btn btn-primary" @click="create"><i class="fas fa-plus-square"></i></button>
            </div>
            <div class="col-auto d-flex">
                <div class="form-group" style="margin-bottom: 0;">
                    <!-- <filter-search v-model="filter.searchtext" @input="fetch()"></filter-search> -->
                </div>
                <button class="btn btn-secondary ml-1" @click="filter.show = !filter.show"><i class="fas fa-filter"></i></button>
            </div>
        </div>

        <form v-if="filter.show" id="filter" class="mt-3">
            <div  class="form-row">

                <filter-tags :options="tags" v-model="filter.tags" @input="fetch"></filter-tags>
                <filter-team :options="team" v-model="filter.team" @input="fetch"></filter-team>
                <filter-per-page v-model="filter.perPage" @input="fetch"></filter-per-page>

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
        <div class="table-responsive mt-3" v-else-if="items.length">
            <table class="table table-hover table-striped bg-white">
                <thead>
                    <tr>
                        <th class="text-center" width="5%">
                            <label class="form-checkbox" for="checkall"></label>
                            <input id="checkall" type="checkbox" v-model="selectAll">
                        </th>
                        <th width="10%">Datum</th>
                        <th width="25%">Mitarbeiter</th>
                        <th width="25%">Dienstleistung</th>
                        <th width="20%">Tags</th>
                        <th class="text-right" width="10%">Dauer</th>
                        <th class="text-right" width="10%">Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(item, index) in items">
                        <row :item="item" :key="item.id" uri="zeiten" :selected="(selected.indexOf(item.id) == -1) ? false : true" @deleted="remove(index)" @input="toggleSelected" @edit="editing(index, $event)" @stopped="updated(index, $event)"></row>
                    </template>
                </tbody>
                <tfoot v-show="selected.length > 0">
                    <tr>
                        <td class="align-middle text-center">{{ selected.length }} ausgewählt</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="align-middle" colspan="2">
                            <select class="form-control" v-model="action">
                                <option value="0">Aktion</option>
                                <option value="invoice">Rechnung erstellen</option>
                            </select>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="alert alert-dark mt-3" v-else><center>Keine Zeiten vorhanden</center></div>
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
        <edit :model="edit.item" :team="team" @created="created($event)" @updated="updated(edit.index, $event)"></edit>
    </div>
</template>

<script>
    import edit from "./edit.vue";
    import filterPerPage from "../filter/perPage.vue";
    import filterSearch from "../filter/search.vue";
    import filterTags from "../filter/tags.vue";
    import filterTeam from "../filter/team.vue";
    import row from "./row.vue";

    export default {

        components: {
            edit,
            filterPerPage,
            filterSearch,
            filterTags,
            filterTeam,
            row,
        },

        props: [
            'tags',
            'team',
        ],

        data () {
            return {
                uri: '/zeiten',
                items: [],
                isLoading: true,
                page: 1,
                paginate: {
                    nextPageUrl: null,
                    prevPageUrl: null,
                    lastPage: 0,
                },
                filter: {
                    show: false,
                    searchtext: '',
                    tags: [],
                    perPage: 25,
                    team: 0,
                },
                selected: [],
                action: '0',
                edit: {
                    index: 0,
                    item: null,
                },
            };
        },

        mounted() {

            this.fetch();

        },

        watch: {
            action(newValue, oldValue) {
                if (newValue == 0) {
                    return;
                }

                this[newValue]();
            },
            page () {
                this.fetch();
            },
        },

        computed: {
            selectAll: {
                get: function () {
                    return this.items.length ? this.items.length == this.selected.length : false;
                },
                set: function (value) {
                    this.selected = [];
                    if (value) {
                        for (let i in this.items) {
                            this.selected.push(this.items[i].id);
                        }
                    }
                },
            },
        },

        methods: {
            create() {
                this.edit.index = 0;
                this.edit.item = null;
                $('#time-edit').modal('show');
            },
            created(time) {
                this.items.unshift(time);
            },
            editing(index, time) {
                this.edit.index = index;
                this.edit.item = time;
                $('#time-edit').modal('show');
            },
            updated(index, time) {
                Vue.set(this.items, index, time)
            },
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get(component.uri, {
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
                        component.isLoading = false;
                        Vue.error('Daten konnten nicht geladen werden!');
                });
            },
            remove(index) {
                this.items.splice(index, 1);
            },
            toggleSelected (id) {
                var index = this.selected.indexOf(id);
                if (index == -1) {
                    this.selected.push(id);
                }
                else {
                    this.selected.splice(index, 1);
                }
            },
            invoice() {
                var component = this;
                axios.post(component.uri + '/abrechnen', {
                    time_ids: component.selected,
                })
                    .then(function (response) {
                        location.href = response.data.path;
                    })
                    .catch(function (error) {
                        Vue.error('Zeiten konnten nicht abgerechnet werden!');
                    })
                    .then( function() {
                        component.action = '0';
                });
            },
        },
    };
</script>