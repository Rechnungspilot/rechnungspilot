<template>
    <div class="d-flex align-items-center justify-content-between px-0" v-if="isCreating">
        <input :class="{'is_invalid': 'name' in errors}" type="text" v-model="form.name" @keydown.enter="create">
        <div class="invalid-feedback" v-text="'name' in errors ? errors.abbreviation[0] : ''"></div>
        <div>
            <i class="fas fa-fw fa-save pointer" @click="create"></i>
            <i class="fas fa-fw fa-times pointer" @click="isCreating = false"></i>
        </div>
    </div>
    <div class="pointer" @click="isCreating = true" v-else>
        <i class="fas fa-fw fa-plus-square text-muted"></i>
        <a class="text-muted ml-1">Aufgabe</a>
    </div>
</template>

<script>
    export default {

        props: [
            'todoId',
        ],

        data() {
            return {
                isCreating: false,
                errors: {},
                form: {
                    name: '',
                    todo_id: this.todoId,
                },
                uri: '/aufgaben',
            };
        },

        methods: {
            create() {
                var component = this;
                axios.post(component.uri, component.form)
                    .then(function (response) {
                        component.$emit('created', response.data);
                        component.form.name = '';
                        component.isCreating = false;
                    })
                    .catch(function (error) {
                        console.log(error);
                });
            },
        },
    };
</script>