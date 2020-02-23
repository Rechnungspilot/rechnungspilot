<template>
    <div class="container-fluid">
        <div class="row flex-nowrap align-items-start">
            <show v-for="(item, index) in items" :key="item.id" :item="item" :should-edit="item.shouldEdit == undefined ? false : item.shouldEdit" @updated="updated(index, $event)" @deleted="remove(index)"></show>
            <create :uri="uri" @created="created($event)"></create>
        </div>
    </div>
</template>

<script>
    import show from './show.vue';
    import create from './create.vue';

    export default {

        components: {
            create,
            show,
        },

        props: [
            'projectId',
        ],

        data() {
            return {
                uri: '/projekte/' + this.projectId + '/abschnitte',
                items: [],
                isLoading: false,
            };
        },

        mounted() {

            this.fetch();

        },

        methods: {
            created(section) {
                section['shouldEdit'] = true;
                this.items.push(section);
            },
            updated(index, section) {
                Vue.set(this.items, index, section);
            },
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get(component.uri)
                    .then(function (response) {
                        component.items = response.data;
                        component.isLoading = false;
                    })
                    .catch(function (error) {
                        console.log(error);
                });
            },
            remove(index) {
                this.items.splice(index, 1);
            },
        },
    };
</script>