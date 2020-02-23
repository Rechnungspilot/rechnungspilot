<template>
    <div>
        <list-item v-for="(item, index) in items" :key="item.id" :item="item"></list-item>
        <create :class="{'mt-3': items.length}" :todo-id="todoId" @created="created($event)"></create>
    </div>
</template>

<script>
    import listItem from './listItem.vue';
    import create from './create.vue';

    export default {
        components: {
            listItem,
            create,
        },

        props: [
            'subtodos',
            'todoId',
        ],

        data() {
            return {
                items: this.subtodos,
            };
        },

        methods: {
            created(item) {
                this.items.push(item);
            },
            updated(index, item) {
                Vue.set(this.items, index, item);
            },
        },

    };
</script>