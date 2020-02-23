<template>
    <div>
        <div class="row">
            <div class="col">
                <button class="btn btn-primary" @click="create"><i class="fas fa-plus-square"></i></button>
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

                <filter-contact :options="contacts" v-model="filter.contact_id" @input="fetch"></filter-contact>
                <filter-status :options="statuses" v-model="filter.status_type" @input="fetch"></filter-status>
                <filter-tags :options="tags" v-model="filter.tags" @input="fetch"></filter-tags>

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
                        <th width="5%">
                            <label class="form-checkbox" for="checkall"></label>
                            <input id="checkall" type="checkbox" v-model="selectAll">
                        </th>
                        <th width="10%">Datum</th>
                        <th width="10%">#</th>
                        <th width="10%">Empfänger</th>
                        <th width="15%">Tags</th>
                        <th class="text-right" width="20%">Netto</th>
                        <th class="text-right" width="20%">Brutto</th>
                        <th width="20%">Status</th>
                        <th class="text-right" width="10%">Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(invoice, index) in items">
                        <row :item="invoice" :key="invoice.id" :uri="'rechnungen'" :selected="(selected.indexOf(invoice.id) == -1) ? false : true" @deleted="remove(index)" @input="toggleSelected"></row>
                    </template>
                </tbody>
            </table>
        </div>
        <div class="alert alert-dark mt-3" v-else><center>Keine Rechnungen vorhanden</center></div>
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
    import row from "../row.vue";
    import filterStatus from "../../filter/status.vue";
    import filterContact from "../../filter/contact.vue";
    import filterTags from "../../filter/tags.vue";
    import filterSearch from "../../filter/search.vue";

    export default {

        components: {
            row,
            filterStatus,
            filterContact,
            filterTags,
            filterSearch,
        },

        props: [
            'contacts',
            'initialFilter',
            'statuses',
            'tags',
        ],

        data () {
            return {
                uri: '/rechnungen',
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
                    contact_id: 0,
                    status_type: 0,
                    tags: [],
                    searchtext: '',
                },
                selected: [],
            };
        },

        mounted() {

            this.fetch();
            // this.setInitialFilters();

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
                axios.post(component.uri)
                    .then(function (response) {
                        location.href = response.data.path;
                    })
                    .catch( function (error) {
                        Vue.error('Rechnung konnte nicht erstellt werden!');
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
            setInitialFilters() {
                for (var key in this.filter) {
                    if (key in this.initialFilter) {
                        this.filter[key] = this.initialFilter[key];
                    }
                }
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