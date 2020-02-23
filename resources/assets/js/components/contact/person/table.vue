<template>
    <div>
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="form-group" style="margin-bottom: 0;">
                    <button class="btn btn-primary" title="Anlegen" @click="create"><i class="fas fa-plus-square"></i></button>
                </div>
            </div>
            <div class="row">
                <div class="form-group" style="margin-bottom: 0;">
                    <input type="search" class="form-control" v-model="searchtext" @keyup="search" placeholder="suchen..">
                </div>&nbsp;
                <button class="btn btn-outline-primary" @click="showFilter = !showFilter">+ Filter</button>
            </div>
            <form v-if="showFilter" id="filter" style="padding: 15px 0;">

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
                    <th class="align-middle" width="5%">Anrede</th>
                    <th class="align-middle" width="15%">Nachname</th>
                    <th class="align-middle" width="15%">Vorname</th>
                    <th class="align-middle" width="10%">Telefon</th>
                    <th class="align-middle" width="10%">Mobil</th>
                    <th class="align-middle" width="15%">E-Mail</th>
                    <th class="align-middle" width="10%">Funktion</th>
                    <th class="align-middle" width="5%">Standard Angebot</th>
                    <th class="align-middle" width="5%">Standard Rechnung</th>
                    <th class="align-middle text-right" width="10%">Aktion</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(item, index) in items">
                    <row :item="item" :key="item.id" @deleted="remove(index)" @setDefault="setDefault"></row>
                </template>
            </tbody>
        </table>
        <div class="alert alert-dark" v-else><center>Keine Ansprechpartner vorhanden</center></div>
    </div>
</template>

<script>
    import row from "./row.vue";

    export default {

        components: {
            row
        },

        props: [
            'contactId',
        ],

        data () {
            return {
                uri: '/kontakte/' + this.contactId + '/ansprechpartner',
                items: [],
                isLoading: true,
                showFilter: false,
                searchtext: '',
                searchTimeout: null,
                filter: {

                },
                errors: {},
            };
        },

        mounted() {

            this.fetch();

        },

        methods: {
            create() {
                var component = this;
                axios.post(this.uri)
                    .then(function (response) {
                        component.items.unshift(response.data);
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                });
            },
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get(this.uri + '?searchtext=' + component.searchtext)
                    .then(function (response) {
                        component.items = response.data;
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
            setDefault(type, itemId, value) {
                for (var key in this.items) {
                    this.items[key]['default_' + type] = (this.items[key].id == itemId && value) ? 1 : 0;
                }
            }
        },
    };
</script>