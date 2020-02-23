<template>
    <div class="modal fade" tabindex="-1" role="dialog" id="account-create">
        <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Bankkonto hinzufügen</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input v-model="name" class="form-control" :class="'name' in errors ? 'is-invalid' : ''" placeholder="Name des Kontos" autofocus></input>
                            <div class="invalid-feedback" v-text="'name' in errors ? errors.name[0] : ''"></div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" @click="create">Anlegen</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import moment from "moment";
    import currencyInput from '../form/input/currency.vue';

    export default {

        components: {
            currencyInput,
        },

        props: [
            'transaction',
        ],

        data() {
            return {
                uri: '/konten',
                name: '',
                errors: {},
            };
        },

        methods: {
            create() {
                var component = this;
                axios.post(component.uri, {
                    name: component.name,
                })
                    .then(function (response) {
                        component.errors = {};
                        component.name = '';
                        component.$emit('created', response.data);
                        $('#account-create').modal('hide');
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                });
            },
        },

    };
</script>