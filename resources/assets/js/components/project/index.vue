<template>
    <div>
        <card v-for="(item, index) in items" :key="item.id" :item="item"></card>
        <create :uri="uri" :project-group-id="projectGroupId" @created="created($event)"></create>
    </div>
</template>

<script>
    import card from './card.vue';
    import create from './create.vue';

    export default {
        components: {
            card,
            create,
        },

        props: [
            'projectGroupId',
        ],

        data() {
            return {
                uri: '/projekte',
                items: [],
                isLoading: false,
            };
        },

        mounted() {

            this.fetch();

        },

        methods: {
            created(item) {
                this.items.push(item);
            },
            updated(index, item) {
                Vue.set(this.items, index, item);
            },
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get(component.uri + '?project_group_id=' + component.projectGroupId)
                    .then(function (response) {
                        component.items = response.data;
                        component.isLoading = false;
                        component.$emit('fetched', component.items.length);
                    })
                    .catch(function (error) {
                        console.log(error);
                });
            },
        },

    };
</script>