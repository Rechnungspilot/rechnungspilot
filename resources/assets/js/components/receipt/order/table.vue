<template>
    <div>
        <div class="container-fluid">
            <div class="row">
                <button class="btn btn-primary" @click="create"><i class="fas fa-plus-square"></i></button>&nbsp;
                <div class="form-group" style="margin-bottom: 0;">
                    <input type="search" class="form-control" v-model="searchtext" @keyup="search" placeholder="suchen..">
                </div>&nbsp;
                <button class="btn btn-outline-primary" @click="showFilter = !showFilter">+ Filter</button>
            </div>
            <form v-if="showFilter" id="filter" class="py-3">
                <div  class="form-row">

                    <filter-contact :options="contacts" v-model="filter.contactId" @input="fetch"></filter-contact>
                    <filter-status :options="statuses" v-model="filter.statusType" @input="fetch"></filter-status>
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
        <div class="table-responsive" v-else-if="items.length">
            <table class="table table-hover table-striped bg-white">
                <thead>
                    <tr>
                        <th width="5%">
                            <label class="form-checkbox" for="checkall"></label>
                            <input id="checkall" type="checkbox" v-model="selectAll">
                        </th>
                        <th width="10%">Datum</th>
                        <th width="15%">Auftragsnummer</th>
                        <th width="10%">Auftraggeber</th>
                        <th width="10%">Tags</th>
                        <th class="text-right" width="20%">Netto</th>
                        <th class="text-right" width="20%">Brutto</th>
                        <th width="20%">Status</th>
                        <th class="text-right" width="10%">Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(invoice, index) in items">
                        <row :item="invoice" :key="invoice.id" :uri="'auftraege'" :selected="(selected.indexOf(invoice.id) == -1) ? false : true" @deleted="remove(index)" @input="toggleSelected"></row>
                    </template>
                </tbody>
            </table>
        </div>
        <div class="alert alert-dark" v-else><center>Keine Aufträge vorhanden</center></div>
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
    import filterPerPage from "../../filter/perPage.vue";

    export default {

        components: {
            filterContact,
            filterPerPage,
            filterStatus,
            filterTags,
            row,
        },

        props: [
            'contacts',
            'statuses',
            'tags',
        ],

        data () {
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
                    contactId: 0,
                    statusType: 0,
                    tags: [],
                    perPage: 25,
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
                axios.post('/auftraege')
                    .then(function (response) {
                        location.href = response.data.path;
                    });
            },
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get('/auftraege?searchtext=' + component.searchtext + '&page=' + component.page + '&contact_id=' + component.filter.contactId + '&status_type=' + component.filter.statusType + '&tags=' + component.filter.tags + '&perPage=' + component.filter.perPage)
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