<template>
    <div class="form-group>">
        <label>Neue Aufgabe</label>
        <input class="form-control" :class="{'is_invalid': 'name' in errors}" type="text" v-model="form.name" @keydown.enter="create" placeholder="Name">
        <div class="invalid-feedback" v-text="'name' in errors ? errors.name[0] : ''"></div>
        <div class="my-1">
            <button class="btn btn-secondary btn-sm"><i class="fas fa-fw fa-save pointer" @click="create"></i></button>
            <button class="btn btn-link btn-sm text-muted"><i class="fas fa-fw fa-times pointer" @click="$emit('canceled')"></i></button>
        </div>
    </div>
</template>

<script>
    export default {

        props: {
            orderId: {
                default: 0,
            },
            itemId: {
                default: null,
            },
        },

        data() {
            return {
                isCreating: false,
                errors: {},
                form: {
                    name: '',
                    item_id: this.itemId ? this.itemId : null,
                },
                uri: '/aufgaben',
            };
        },

        watch: {
            itemId(newValue) {
                this.form.item_id = newValue ? newValue : null;
            },
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