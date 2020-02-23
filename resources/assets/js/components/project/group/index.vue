<template>
    <div class="container-fluid">
        <div class="row flex-nowrap align-items-start">
            <group v-for="(item, index) in items" :key="item.id" :item="item" :should-edit="item.shouldEdit == undefined ? false : item.shouldEdit" @updated="updated(index, $event)" @deleted="remove(index)"></group>
            <create :uri="uri" @created="created($event)"></create>
        </div>
    </div>
</template>

<script>
    import group from './show.vue';
    import create from './create.vue';

    export default {

        components: {
            create,
            group,
        },

        data() {
            return {
                uri: '/projektgruppen',
                items: [],
                isLoading: false,
            };
        },

        mounted() {

            this.fetch();

        },

        methods: {
            created(group) {
                group['shouldEdit'] = true;
                this.items.push(group);
            },
            updated(index, group) {
                Vue.set(this.items, index, group);
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