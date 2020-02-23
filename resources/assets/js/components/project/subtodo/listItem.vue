<template>
    <div>
        <i class="fas fa-fw fa-check-square text-success pointer" @click="incompleted()" v-if="form.completed == true"></i>
        <i class="far fa-fw fa-square pointer" @click="completed()" v-else></i>
        <a :href="item.path" class="text-body ml-1">{{ item.name }}</a>
    </div>
</template>

<script>
    export default {
        props: [
            'item',
        ],

        data() {
            return {
                form: {
                    completed: this.item.completed,
                },
                completedUri: '/aufgaben/' + this.item.id + '/erledigt',
            };
        },

        methods: {
            link() {
                location.href = this.item.path;
            },
            completed() {
                var component = this;
                component.form.completed = true;
                axios.post(component.completedUri, component.form);
            },
            incompleted() {
                var component = this;
                component.form.completed = false;
                axios.delete(component.completedUri, component.form);
            },
        },
    };
</script>