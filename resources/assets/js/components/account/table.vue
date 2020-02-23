<template>
    <div>
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="account-create-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-plus-square"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="account-create-dropdown">
                        <a class="dropdown-item" href="#" @click.prevent="create('account')">Manuelles Konto</a>
                        <a class="dropdown-item" href="#" @click.prevent="create('bank-company')">Bankkonto verknüpfen</a>
                    </div>
                </div>
            </div>
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
                        <th width="20%">Name</th>
                        <th width="10%" class="text-right">Saldo</th>
                        <th width="60%" class="text-right"></th>
                        <th width="10%" class="text-right">Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    <row v-for="(item, index) in items" :item="item" :key="item.id" :uri="uri" @updated="update(index, $event)" @deleted="remove(index)"></row>
                </tbody>
            </table>
        </div>
        <div class="alert alert-dark" v-else><center>Keine Konten vorhanden</center></div>
        <create @created="created($event)"></create>
        <bank-company-create @created="created($event)"></bank-company-create>
    </div>
</template>

<script>
    import create from "./create.vue";
    import BankCompanyCreate from "../bank/company/create.vue";
    import row from "./row.vue";
    import filterTags from "../filter/tags.vue";

    export default {

        components: {
            create,
            BankCompanyCreate,
            row,
            filterTags,
        },

        props: [
            'accounts',
            'tags',
        ],

        data () {
            return {
                uri: '/konten',
                items: [],
                isLoading: true,
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
            create(type) {
                $('#' + type + '-create').modal('show');
            },
            created(account) {
                this.items.push(account);
            },
            remove(index) {
                this.items.splice(index, 1);
            },
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get(this.uri)
                    .then(function (response) {
                        component.items = response.data;
                        component.isLoading = false;
                    })
                    .catch(function (error) {
                        console.log(error);
                });
            },
        },
    };
</script>