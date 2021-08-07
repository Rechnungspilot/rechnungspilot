<template>

    <div>
        <h4>Kontakt wählen</h4>

        <contact-list v-model="form.contact_id" :index-path="contactIndexPath"></contact-list>

        <div class="row my-5"><div class="col"></div></div>

        <div class="fixed-bottom bg-white p-3 text-right">
            <button type="submit" class="btn btn-primary btn-sm" :disabled="form.contact_id == 0" @click="create">Anlegen</button>
        </div>

    </div>

</template>

<script>
    import contactList from '../../contact/list.vue';

    export default {

        components: {
            contactList,
        },

        props: {
            indexPath: {
                type: String,
                required: true,
            },
            contactIndexPath: {
                type: String,
                required: true,
            },
            invoiceIndexPath: {
                type: String,
                required: true,
            },
            tags: {
                required: true,
                type: Array,
            },
        },

        data () {
            return {
                form: {
                    contact_id: 0,
                },
            };
        },

        methods: {
            create() {
                var component = this;
                axios.post(component.indexPath, component.form)
                    .then(function (response) {
                        location.href = component.indexPath + '/' + response.data.id;
                })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                        Vue.errorCreate();
                });
            },
        },
    };
</script>