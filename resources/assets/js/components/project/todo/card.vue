<template>
    <div class="card my-3">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <i class="fas fa-fw fa-check-square text-success pointer" @click="incompleted()" v-if="form.completed == true"></i>
                    <i class="far fa-fw fa-square pointer" @click="completed()" v-else></i>
                    <a :href="item.path" class="text-body ml-1">{{ item.name }}</a>
                </div>
                <div>
                    <i class="fas fa-user-circle pointer" v-show="false"></i>
                </div>
            </div>

            <div v-if="item.tags.length">
                <span class="badge badge-secondary mr-1" v-for="tag in item.tags">{{ tag.name }}</span>
            </div>
        </div>
        <div class="card-footer" v-if="item.todoable_type != 'App\\Todos\\Todo'">
            <subtodos :todo-id="item.id" :subtodos="item.subtodos"></subtodos>
        </div>
    </div>
</template>

<script>
    import subtodos from '../subtodo/index.vue';

    export default {

        components: {
            subtodos,
        },

        props: {
            item: {
                type: Object,
                required: true,
            },
        },

        watch: {
            isCompleted(newValue) {
                this.form.completed = newValue;
            },
        },

        computed: {
            isCompleted() {
                return this.item.completed;
            },
        },

        data() {
            return {
                form: {
                    completed: this.item.completed,
                },
                completedUri: this.item.path + '/erledigt',
            };
        },

        methods: {
            link() {
                location.href = this.item.path;
            },
            completed() {
                var component = this;
                axios.post(component.completedUri, component.form)
                    .then( function (response) {
                        component.form.completed = true;
                        component.$emit('completed', component.form.completed);
                        Vue.success('Aufgabe ist erledigt');
                    })
                    .catch (function (error) {
                        console.log(error);
                });
            },
            incompleted() {
                var component = this;
                axios.delete(component.completedUri, component.form)
                    .then( function (response) {
                        component.form.completed = false;
                        component.$emit('completed', component.form.completed);
                        Vue.success('Aufgabe ist unerledigt');
                    })
                    .catch (function (error) {
                        console.log(error);
                });
            },
        },
    };
</script>