<template>
    <div class="container-fluid">
        <div class="row flex-nowrap align-items-start">
            <div class="project-show bg-light">

                <div class="form-group">
                    <input type="text" class="form-control" :class="'name' in errors ? 'is-invalid' : ''" v-model="form.name">
                    <div class="invalid-feedback" v-text="'name' in errors ? errors.name[0] : ''"></div>
                </div>
                <div class="form-group">
                    <textarea class="form-control" :class="'description' in errors ? 'is-invalid' : ''" rows="10" v-model="form.description"></textarea>
                    <div class="invalid-feedback" v-text="'description' in errors ? errors.description[0] : ''"></div>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <button class="btn btn-text text-muted px-0" @click="destroy()">Löschen</button>
                    <button class="btn btn-secondary" @click="edit"><i class="fas fa-fw fa-save"></i></button>
                </div>

            </div>
            <div class="col-md-10">
                <sections :project-id="item.id"></sections>
            </div>
        </div>
    </div>
</template>

<script>
    import sections from './section/index.vue';

    export default {

        components: {
            sections,
        },

        props: [
            'item',
        ],

        data() {
            return {
                errors: {},
                form: {
                    description: this.item.description,
                    name: this.item.name,
                },
            };
        },

        methods: {
            edit() {
                var component = this;
                axios.put(component.item.path, component.form)
                    .then( function (response) {
                        component.errors = {};
                        Vue.success('Projekt gespeichert.')
                    })
                    .catch( function (error) {
                        component.errors = error.response.data.errors;
                });
            },
            destroy() {
                var component = this;
                axios.delete(component.item.path)
                    .then( function (response) {
                        location.href = '/projekte';
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                });
            },
        },
    };
</script>