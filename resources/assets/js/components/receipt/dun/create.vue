<template>
    <div class="modal fade" tabindex="-1" role="dialog" id="dun-create">
        <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Mahnung erstellen</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6>Rechnungen</h6>
                            <button class="btn btn-secondary btn-sm" @click.prevent="fetch">
                                <i class="fas fa-fw fa-sync pointer"></i>
                            </button>
                        </div>
                        <div v-if="isLoading" class="p-5">
                            <center>
                                <span style="font-size: 48px;">
                                    <i class="fas fa-spinner fa-spin"></i><br />
                                </span>
                                Lade Daten..
                            </center>
                        </div>
                        <div class="table-responsive" v-else-if="items.length">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="align-middle" width="10%">&nbsp;</th>
                                        <th class="align-middle" width="20%">Rechnung</th>
                                        <th class="align-middle" width="10%">Datum</th>
                                        <th class="align-middle" width="10%">Fällig</th>
                                        <th class="align-middle text-right" width="15%">Betrag</th>
                                        <th class="align-middle text-right" width="15%">Offen</th>
                                        <th class="align-middle" width="20%">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="pointer" v-for="(item, index) in items" @click="toggle(item.id)">
                                        <td class="align-middle text-center" v-if="itemId == item.id">
                                            <i class="fas fa-fw fa-check-circle text-success"></i>
                                        </td>
                                        <td class="align-middle text-center" v-else>
                                            <i class="fas fa-fw fa-circle text-light"></i>
                                        </td>
                                        <td class="align-middle">
                                            {{ item.name }}<br />
                                            <span class="text-muted">{{ item.contact.name }}</span>
                                        </td>
                                        <td class="align-middle">
                                            {{ date(item.date) }}</span>
                                        </td>
                                        <td class="align-middle">
                                            {{ date(item.date_due) }}</span>
                                        </td>
                                        <td class="align-middle text-right">
                                            {{ (item.gross / 100).format(2, ',', '.') }} €</span>
                                        </td>
                                        <td class="align-middle text-right">
                                            {{ (item.outstanding / 100).format(2, ',', '.') }} €</span>
                                        </td>
                                        <td class="align-middle">{{ item.latest_dun_id == 0 ? '' : item.latest_dun.settings.level.name }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="alert alert-dark" v-else><center>Keine überfälligen Rechnungen vorhanden</center></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" :disabled="itemId == 0" @click.prevent="create">Mahnung erstellen</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import moment from "moment";

    export default {

        components: {

        },

        props: [

        ],

        data() {
            return {
                uri: '/mahnungen/rechnungen',
                items: [],
                itemId: 0,
                errors: {},
                isLoading: true,
            };
        },

        mounted() {
            this.fetch();
        },

        methods: {
            create() {
                var component = this;
                axios.post('/rechnungen/' + component.itemId + '/mahnungen')
                    .then(function (response) {
                        component.errors = {};
                        component.$emit('created', response.data);
                        $('#dun-create').modal('hide');
                        location = response.data.path + '/edit';
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                });
            },
            date(date) {
                return moment(date).format('DD.MM.YYYY');
            },
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get(component.uri)
                    .then(function (response) {
                        component.errors = {};
                        component.items = response.data;
                        component.isLoading = false;
                    })
                    .catch(function (error) {
                        console.log(error);
                });
            },
            toggle(value) {
                if (this.itemId == value) {
                    this.itemId = 0;
                    return;
                }
                this.itemId = value;

            },
        },

    };
</script>