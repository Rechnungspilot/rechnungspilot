<template>
    <div>
        <div class="container-fluid">
            <div class="row mb-3">
                <create @created="created"></create>
            </div>
            <div class="row">
                <div class="form-group" style="margin-bottom: 0;">
                    <input type="search" class="form-control" v-model="searchtext" @keyup="search" placeholder="suchen..">
                </div>&nbsp;
                <button class="btn btn-outline-primary" @click="showFilter = !showFilter">+ Filter</button>
            </div>
            <form v-if="showFilter" id="filter" style="padding: 15px 0;">
                <div  class="form-row">
                    <filter-type :options="filterTypes" v-model="filter.type" @input="fetch"></filter-type>
                    <filter-tags :options="filterTags" v-model="filter.tags" @input="fetch"></filter-tags>
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
                    <th width="30%">Bezeichnung</th>
                    <th width="30%">Datensatz</th>
                    <th width="30%">Tags</th>
                    <th class="text-right" width="10%">Aktion</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(item, index) in items">
                    <row :item="item" :uri="uri" :key="item.id" @deleted="remove(index)" @updated="updated(index, $event)"></row>
                </template>
            </tbody>
        </table>
        <div class="alert alert-dark" v-else><center>Keine Dateien vorhanden</center></div>
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
    import create from "./create.vue";
    import row from "./row.vue";
    import filterType from "../filter/type.vue";
    import filterTags from "../filter/tags.vue";

    export default {

        components: {
            create,
            row,
            filterType,
            filterTags,
        },

        props: [
            'token',
            'filterTypes',
            'filterTags',
        ],

        data () {
            var uri = '/dateien';
            return {
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
                    type: '',
                    tags: [],
                },
                name: '',
                errors: {},
                uri: uri,
                action: uri,
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
            created(files) {
                for (var index in files) {
                    this.items.unshift(files[index]);
                }
            },
            updated(index, item) {
                Vue.set(this.items, index, item);
            },
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get(component.uri + '?searchtext=' + component.searchtext + '&fileable_type=' + component.filter.type + '&tags=' + component.filter.tags)
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
        },
    };
</script>

<style>
    .filezone {
        outline: 2px dashed grey;
        outline-offset: -10px;
        background: #ccc;
        color: dimgray;
        padding: 10px 10px;
        min-height: 200px;
        position: relative;
        cursor: pointer;
    }
    .filezone:hover {
        background: #c0c0c0;
    }
</style>