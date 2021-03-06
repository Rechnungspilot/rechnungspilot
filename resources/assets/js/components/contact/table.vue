<template>
    <div>
        <div class="row mb-3">
            <div class="col d-flex align-items-start mb-1 mb-sm-0">
                <div class="form-group mb-0 mr-1">

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
            <div class="form-row">

                <filter-tags :options="tags" v-model="filter.tags" @input="fetch"></filter-tags>
                <filter-per-page v-model="filter.perPage" @input="fetch"></filter-per-page>

            </div>
        </form>

        <div v-if="isLoading" class="p-5">
            <center>
                <span style="font-size: 48px;">
                    <i class="fas fa-spinner fa-spin"></i><br />
                </span>
                Lade Daten..
            </center>
        </div>
        <table class="table table-hover table-striped table-sm bg-white" v-else-if="items.length">
            <thead>
                <tr>
                    <th width="30">
                        <label class="form-checkbox" for="checkall"></label>
                        <input id="checkall" type="checkbox" v-model="selectAll">
                    </th>
                    <th width="40%">Name</th>
                    <th width="30%">Straße</th>
                    <th width="50">PLZ</th>
                    <th width="30%">Ort</th>
                    <th width="100">Umsatz</th>
                    <th class="text-right" width="125">Aktion</th>
                </tr>
            </thead>
            <tbody>
                <row :item="item" :key="item.id" :uri="uri" :selected="(selected.indexOf(item.id) == -1) ? false : true" v-for="(item, index) in items" @deleted="remove(index)" @input="toggleSelected"></row>
            </tbody>
        </table>
        <div class="alert alert-dark" v-else><center>Keine Kontakte vorhanden</center></div>
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
    import filterPerPage from "../filter/perPage.vue";
    import filterSearch from "../filter/search.vue";

    export default {

        components: {
            row,
            filterTags,
            filterPerPage,
            filterSearch,
        },

        props: [
            'tags',
        ],

        data () {
            return {
                uri: '/kontakte',
                items: [],
                isLoading: true,
                searchTimeout: null,
                paginate: {
                    nextPageUrl: null,
                    prevPageUrl: null,
                    lastPage: 0,
                },
                filter: {
                    page: 1,
                    show: false,
                    tags: [],
                    perPage: 25,
                    searchtext: '',
                },
                selected: [],
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
                axios.post(this.uri)
                    .then(function (response) {
                        location.href = component.uri + '/' + response.data.id + '/edit';
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