<template>
    <div>
        <tan-create :tan="tan" @success=""></tan-create>
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="form-group" style="margin-bottom: 0;">
                        <input type="search" class="form-control" v-model="searchtext" @keyup="search" placeholder="suchen..">
                    </div>
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-primary" @click="showFilter = !showFilter">+ Filter</button>
                    <button class="btn btn-outline-secondary" @click="sync" v-show="hasBank"><i class="fas fa-fw fa-sync"></i></button>
                </div>
            </div>
            <form v-if="showFilter" id="filter" class="py-3">
                <div  class="form-row">

                    <filter-account :options="accounts" v-model="filter.accountId" @input="fetch"></filter-account>
                    <!-- <filter-tags :options="tags" v-model="filter.tags" @input="fetch"></filter-tags> -->

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
                    <th width="10%">Datum</th>
                    <th width="25%">Sender/Empfänger</th>
                    <th width="30%">Verwendungszweck</th>
                    <th width="15%">Kategorien</th>
                    <th width="10%">Betrag</th>
                    <th class="text-right" width="10%">Aktion</th>
                </tr>
            </thead>
            <tbody>
                <row v-for="(item, index) in items" :item="item" :key="item.id" :uri="uri" @edit="editTransaction(index, $event)"></row>
            </tbody>
        </table>
        <div class="alert alert-dark" v-else><center>Keine Buchungen vorhanden</center></div>
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
        <edit :transaction="edit.item" @updated="updated($event)"></edit>
    </div>
</template>

<script>
    import row from "./row.vue";
    import edit from "./edit.vue";
    import filterAccount from "../filter/account.vue";
    import filterTags from "../filter/tags.vue";
    import tanCreate from "../bank/tan/create.vue";

    export default {

        components: {
            row,
            edit,
            filterAccount,
            filterTags,
            tanCreate,
        },

        props: [
            'accounts',
            'tags',
        ],

        computed: {
            currentAccount() {
                var index = Object.keys(this.accounts).find(key => this.accounts[key]['id'] === this.filter.accountId);
                return this.accounts[index];
            },
            hasBank() {
                return (this.currentAccount.bank_company_id > 0);
            }
        },

        data () {
            return {
                uri: '/buchungen',
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
                    accountId: this.accounts[0].id,
                    tags: [],
                },
                edit: {
                    item: [],
                    index: 0,
                },
                tan: {
                    action_path: null,
                    html: '',
                    show: false,
                    tan: '',
                    bank_company_id: 0,
                },
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
            remove(index) {
                this.items.splice(index, 1);
            },
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get(this.uri + '?searchtext=' + component.searchtext + '&page=' + component.page + '&account_id=' + component.filter.accountId + '&tags=' + component.filter.tags)
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
            editTransaction(index, item) {
                this.edit.item = item;
                this.edit.index = index;
                $('#transaction-edit').modal('show');
            },
            updated(transaction) {
                Vue.set(this.items, this.edit.index, transaction)
                $('#transaction-edit').modal('hide');
            },
            sync() {
                var component = this;
                component.isLoading = true;

                axios.get(component.currentAccount.path + '/sync')
                    .then(function (response) {
                        component.isLoading = false;
                        component.tan = response.data.tan;
                        Vue.success('Buchungen erfolgreich synchronisiert');
                        component.fetch();
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        },
    };
</script>