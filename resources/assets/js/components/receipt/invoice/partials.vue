<template>
    <div v-show="possibles.length">
        <ul class="list-group mb-4">
            <li class="list-group-item" v-for="(partial, key) in possibles">
                <input :checked="partial.final_invoice_id == id" :id="key" type="checkbox" class="" @change="toggle"> <a :href="partial.path" target="_blank">{{ partial.name }}</a>
            </li>
        </ul>
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
                return axios.post(this.uri + '/' + receiptId, {
                    finalInvoiceId: this.id,
                });
            },
            destroy(receiptId) {
                return axios.post(this.uri + '/' + receiptId, {
                    finalInvoiceId: null,
                });
            },
        },
    };
</script>