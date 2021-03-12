<template>
    <div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label col-form-label-sm" for="contact_id">Kunde <a class="ml-1 pointer" :href="selectedContact.path"><i class="fas fa-external-link-alt"></i></a></label>
            <div class="col-sm-8">
                <select class="form-control form-control-sm" v-model="contact_id" @change="fetchAddress" name="contact_id">
                    <option v-for="contact in contacts" :value="contact.id">{{ contact.name }}</option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4 col-form-label col-form-label-sm" for="address">Adresse</label>
            <div class="col-sm-8">
                <textarea class="form-control form-control-sm" rows="4" v-model="address" name="address"></textarea>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        props: [
            'contacts',
            'selectedContactId',
            'selectedAddress',
        ],

        data () {
            return {
                contact_id: this.selectedContactId,
                address: this.selectedAddress,
                selectedContact: {},
            };
        },

        mounted() {
            this.findSelectedContact();
        },

        methods: {
            fetchAddress() {
                var component = this;
                component.findSelectedContact();
                component.address = component.selectedContact.billing_address;
                // axios.get('/kontakte/adresse/' + this.contact_id)
                //     .then(function (response) {
                //         component.address = response.data;
                // });

            },
            findSelectedContact() {
                var component = this;
                this.contacts.forEach( function (contact, index) {
                    if (contact.id == component.contact_id) {
                        component.selectedContact = contact;
                    }
                });
            },
        },

    };
</script>