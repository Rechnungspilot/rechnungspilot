<template>
    <div>
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
                    <th width="50%">Datum</th>
                    <th class="text-right" width="50%">Betrag</th>
                </tr>
            </thead>
            <tbody>
                <row v-for="(item, index) in items" :item="item" :key="item.id" :uri="uri"></row>
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
    </div>
</template>

<script>
    import row from "./row.vue";

    export default {

        components: {
            row,
        },

        props: [
            'company',
        ],

        data () {
            return {
                uri: '/firma/guthaben',
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
                    companyId: this.company.id,
                    tags: [],
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
                axios.get(this.uri + '?page=' + component.page + '&account_id=0' + '&company_id=' + component.filter.companyId)
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
        },
    };
</script>