<template>
    <div>
        <div class="row">
            <div class="col">

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
                        <th width="10%">Datum/Fällig</th>
                        <th width="10%">#</th>
                        <th width="15%">Kontakt</th>
                        <th class="text-right" width="10%">Brutto</th>
                        <th class="text-right" width="10%">Offen</th>
                        <th class="text-right" width="10%">Zahlung</th>
                        <th width="10%">Zahlungsdatum</th>
                        <th width="10%">Konto</th>
                        <th class="text-center" width="5%">Erledigt</th>
                        <th class="text-right" width="10%">Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(item, index) in items">
                        <row :item="item" :key="item.id" :accounts="accounts" :uri="uri" :selected="(selected.indexOf(item.id) == -1) ? false : true" @created="created(index, $event)" @input="toggleSelected"></row>
                    </template>
                </tbody>
            </table>
        </div>
        <div class="alert alert-dark mt-3" v-else><center>Keine offenen Zahlungen vorhanden</center></div>
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
    import filterStatus from "../../filter/status.vue";
    import filterContact from "../../filter/contact.vue";
    import filterSearch from "../../filter/search.vue";

    export default {

        components: {
            row,
            filterStatus,
            filterContact,
            filterSearch,
        },

        props: [
            'type',
            'accounts',
            'contacts',
            'initialFilter',
            'labels',
            'statuses',
        ],

        data () {
            return {
                uri: '/' + this.type,
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
            created(index, status) {
                console.log(status);
                if (status.data.completed) {
                    this.items.splice(index, 1);
                }
                else {
                    Vue.set(this.items[index], 'outstanding', this.items[index]['outstanding'] - status.data.amount);
                }
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
                        Vue.success(labels.plural + ' konnten nicht geladen werden!');
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
                Vue.success(this.labels.singular + ' gelöscht.');
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