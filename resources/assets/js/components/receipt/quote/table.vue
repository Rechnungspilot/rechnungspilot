<template>
    <div>
        <div class="container-fluid">
            <div class="row">
                <button class="btn btn-primary" @click="create"><i class="fas fa-plus-square"></i></button>&nbsp;
                <div class="form-group" style="margin-bottom: 0;">
                    <input type="search" class="form-control" v-model="searchtext" @keyup="search" placeholder="Angebote durchsuchen">
                </div>&nbsp;
                <button class="btn btn-outline-primary" @click="showFilter = !showFilter">+ Filter</button>
            </div>
            <div v-if="showFilter" id="filter" class="py-3">
                <div  class="form-row">

                    <filter-contact :options="contacts" v-model="filter.contactId" @input="fetch"></filter-contact>
                    <filter-status :options="statuses" v-model="filter.statusType" @input="fetch"></filter-status>
                    <filter-tags :options="tags" v-model="filter.tags" @input="fetch"></filter-tags>

                </div>
            </div>
        </div>
        <br />
        <div v-if="isLoading" class="p-5">
            <center>Lade Daten..</center>
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
                        <th width="15%">Angebotsnummer</th>
                        <th width="10%">Empfänger</th>
                        <th width="10%">Tags</th>
                        <th class="text-right" width="20%">Netto</th>
                        <th class="text-right" width="20%">Brutto</th>
                        <th width="20%">Status</th>
                        <th class="text-right" width="10%">Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(quote, index) in items">
                        <receipt-table-row :item="quote" :key="quote.id" :uri="'angebote'" :selected="(selected.indexOf(quote.id) == -1) ? false : true" @deleted="remove(index)" @input="toggleSelected"></receipt-table-row>
                    </template>
                </tbody>
            </table>
        </div>
        <div class="alert alert-dark" v-else><center>Keine Angebote vorhanden</center></div>
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
    import receiptTableRow from "../row.vue";
    import filterStatus from "../../filter/status.vue";
    import filterContact from "../../filter/contact.vue";
    import filterTags from "../../filter/tags.vue";


    export default {

        components: {
            receiptTableRow,
            filterStatus,
            filterContact,
            filterTags,
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
                showFilter: false,
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
                axios.post('/angebote')
                    .then(function (response) {
                        location.href = response.data.path;
                    });
            },
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get('/angebote?searchtext=' + component.searchtext + '&page=' + component.page + '&contact_id=' + component.filter.contactId + '&status_type=' + component.filter.statusType + '&tags=' + component.filter.tags)
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