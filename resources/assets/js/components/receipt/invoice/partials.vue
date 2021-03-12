<template>
    <div v-show="possibles.length">
        <div class="form-group row" v-for="(partial, key) in possibles">
            <label class="col-sm-4 col-form-label col-form-label-sm pointer" :for="key">{{ partial.name }}<a class="ml-1" :href="partial.path" target="_blank"><i class="fas fa-external-link-alt"></i></a></label>
            <div class="col-sm-8">
                <div class="form-check">
                    <input :checked="partial.final_invoice_id == id" class="form-check-input" :id="key" type="checkbox" @change="toggle">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'id',
            'possibles',
        ],

        data () {
            return {
                uri: '/abschlagsrechnungen',
                partials: this.possibles,
            }
        },

        methods: {
            toggle(event) {
                var component = this;
                if (event.target.checked) {
                    this.create(this.partials[event.target.id].id)
                        .then( function (response){
                            component.partials[event.target.id].receipt_id = component.id;
                    });
                }
                else {
                    this.destroy(this.partials[event.target.id].id)
                        .then( function (response){
                            component.partials[event.target.id].receipt_id = 0;
                    });
                }
            },
            create(receiptId) {
                axios.post(this.uri + '/' + receiptId, {
                    finalInvoiceId: this.id,
                })
                    .then(function (response) {
                        Vue.success('Als Abschlagsrechnung markiert.');
                })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                        Vue.errorCreate();
                });
            },
            destroy(receiptId) {
                axios.post(this.uri + '/' + receiptId, {
                    finalInvoiceId: null,
                })
                    .then(function (response) {
                        Vue.success('Nicht mehr als Abschlagsrechnung markiert.');
                })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                        Vue.errorCreate();
                });
            },
        },
    };
</script>