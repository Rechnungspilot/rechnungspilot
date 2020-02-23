<template>
    <div>
        <card v-for="(item, index) in items" :key="item.id" :item="item"></card>
        <create :uri="uri" :project-section-id="projectSectionId" @created="created($event)"></create>
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
            'projectSectionId',
        ],

        data() {
            return {
                uri: '/projektabschnitte/' + this.projectSectionId + '/aufgaben',
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
                axios.get(component.uri + '?project_section_id=' + component.projectSectionId)
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