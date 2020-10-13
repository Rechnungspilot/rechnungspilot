<template>
    <div>
        <div class="row mb-3">
            <div class="col d-flex align-items-start mb-1 mb-sm-0">
                <div class="form-group mb-0 mr-1">
                    <div>
                        <input type="text" class="form-control" :class="'name' in errors ? 'is-invalid' : ''" v-model="form.name" placeholder="Name" @keydown.enter="create">
                        <div class="invalid-feedback" v-text="'name' in errors ? errors.name[0] : ''"></div>
                    </div>
                </div>
                <button class="btn btn-primary" @click="create"><i class="fas fa-plus-square"></i></button>
            </div>
            <div class="col-auto d-flex">
                <div class="form-group" style="margin-bottom: 0;">
                    <filter-search v-model="filter.searchtext" @input="search"></filter-search>
                </div>
                <button class="btn btn-secondary ml-1" @click="filter.show = !filter.show"><i class="fas fa-filter"></i></button>
            </div>
        </div>

        <form v-if="filter.show" id="filter" class="py-3">
            <div  class="form-row">

                <filter-type :options="types" v-model="filter.type" @input="search"></filter-type>
                <filter-tags :options="tags" v-model="filter.tags" @input="search"></filter-tags>
                <filter-per-page v-model="filter.perPage" @input="search"></filter-per-page>

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
            <table class="table table-hover table-striped table-sm bg-white">
                <thead>
                    <tr>
                        <th width="30">
                            <label class="form-checkbox" for="checkall"></label>
                            <input id="checkall" type="checkbox" v-model="selectAll">
                        </th>
                        <th class="text-right" width="85">#</th>
                        <th width="100%">Name</th>
                        <th class="text-right" width="75">Brutto</th>
                        <th class="text-right" width="75">Netto</th>
                        <th width="75">Einheit</th>
                        <th width="75">USt.</th>
                        <th class="text-right" width="75">Umsatz</th>
                        <th class="text-right" width="125">Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(item, index) in items">
                        <row :item="item" :key="item.id" :uri="uri" :selected="(selected.indexOf(item.id) == -1) ? false : true" @deleted="remove(index)" @input="toggleSelected"></row>
                    </template>
                </tbody>
            </table>
        </div>
        <div class="alert alert-dark" v-else><center>Keine Artikel vorhanden</center></div>
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
    import filterTags from "../filter/tags.vue";
    import filterType from "../filter/itemtype.vue";
    import filterPerPage from "../filter/perPage.vue";
    import filterSearch from "../filter/search.vue";

    export default {

        components: {
            row,
            filterTags,
            filterType,
            filterPerPage,
            filterSearch,
        },

        props: [
            'tags',
            'types'
        ],

        data () {
            return {
                uri: '/artikel',
                items: [],
                isLoading: true,
                searchTimeout: null,
                paginate: {
                    nextPageUrl: null,
                    prevPageUrl: null,
                    lastPage: 0,
                },
                filter: {
                    show: false,
                    searchtext: '',
                    page: 1,
                    tags: [],
                    type: -1,
                    perPage: 25,
                },
                form: {
                    name: '',
                },
                selected: [],
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

        computed: {
            page() {
                return this.filter.page;
            },
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
                var component = this;
                axios.post(this.uri, {
                    name: this.name,
                })
                    .then(function (response) {
                        location.href = component.uri + '/' + response.data.id + '/edit';
                    });
            },
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get(component.uri, {
                    params: component.filter
                })
                    .then(function (response) {
                        component.items = response.data.data;
                        component.filter.page = response.data.current_page;
                        component.paginate.nextPageUrl = response.data.next_page_url;
                        component.paginate.prevPageUrl = response.data.prev_page_url;
                        component.paginate.lastPage = response.data.last_page;
                        component.isLoading = false;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            search() {
                this.filter.page = 1;
                this.fetch();
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
        },
    };
</script>