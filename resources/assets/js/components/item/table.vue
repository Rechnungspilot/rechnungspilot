<template>
    <div>
        <div class="container-fluid">
            <div class="row mb-3" >
                <div class="form-group mb-0 mr-1">
                    <input type="text" class="form-control" :class="'name' in errors ? 'is-invalid' : ''" v-model="name" placeholder="Neuer Artikel Name" @keydown.enter="create">
                    <div class="invalid-feedback" v-text="'name' in errors ? errors.name[0] : ''"></div>
                </div>
                <div class="form-group mb-0">
                    <button class="btn btn-primary" @click="create"><i class="fas fa-plus-square"></i></button>
                </div>
            </div>
            <div class="row">
                <div class="form-group" style="margin-bottom: 0;">
                    <input type="search" class="form-control" v-model="searchtext" @keyup="search" placeholder="suchen">
                </div>&nbsp;
                <button class="btn btn-outline-primary" @click="showFilter = !showFilter">+ Filter</button>
            </div>
            <form v-if="showFilter" id="filter" class="py-3">
                <div  class="form-row">

                    <filter-type :options="types" v-model="filter.type" @input="fetch"></filter-type>
                    <filter-tags :options="tags" v-model="filter.tags" @input="fetch"></filter-tags>
                    <filter-per-page v-model="filter.perPage" @input="fetch"></filter-per-page>

                </div>
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
        <table class="table table-hover table-striped bg-white" v-else-if="items.length">
            <thead>
                <tr>
                    <th width="5%">
                        <label class="form-checkbox" for="checkall"></label>
                        <input id="checkall" type="checkbox" v-model="selectAll">
                    </th>
                    <th class="text-right" width="5%">#</th>
                    <th width="15%">Name</th>
                    <th class="text-right" width="10%">Brutto</th>
                    <th class="text-right" width="10%">Netto</th>
                    <th width="10%">Einheit</th>
                    <th width="10%">USt.</th>
                    <th width="15%">Kategorien</th>
                    <th class="text-right" width="10%">Umsatz</th>
                    <th class="text-right" width="10%">Aktion</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(item, index) in items">
                    <row :item="item" :key="item.id" :uri="uri" :selected="(selected.indexOf(item.id) == -1) ? false : true" @deleted="remove(index)" @input="toggleSelected"></row>
                </template>
            </tbody>
        </table>
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

    export default {

        components: {
            row,
            filterTags,
            filterType,
            filterPerPage
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
                showFilter: true,
                searchtext: '',
                searchTimeout: null,
                page: 1,
                paginate: {
                    nextPageUrl: null,
                    prevPageUrl: null,
                    lastPage: 0,
                },
                filter: {
                    tags: [],
                    type: -1,
                    perPage: 25,
                },
                selected: [],
                name: '',
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
                axios.get(this.uri + '?searchtext=' + component.searchtext + '&page=' + component.page + '&tags=' + component.filter.tags + '&perPage=' + component.filter.perPage + '&type=' + component.filter.type)
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