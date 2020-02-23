<template>
    <div>
        <div class="form-group">
            <label for="contact_id">Kunde <a class="ml-1 pointer" :href="selectedContact.path"><i class="fas fa-external-link-alt"></i></a></label>
            <select class="form-control" v-model="contact_id" @change="fetchAddress" name="contact_id">
                <option v-for="contact in contacts" :value="contact.id">{{ contact.name }}</option>
            </select>
        </div>

        <div class="form-group">
            <label for="address">Adresse</label>
            <textarea class="form-control" rows="4" v-model="address" name="address"></textarea>
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
                component.address = component.selectedContact.name + "\n" + component.selectedContact.address + "\n" + component.selectedContact.postcode + ' ' + component.selectedContact.city;
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