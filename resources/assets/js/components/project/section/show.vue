<template>
    <div class="project-section">
        <div class="col d-flex align-items-center justify-content-between px-0" style="height: 40px;" v-if="isEditing">
            <input :class="{'is_invalid': 'name' in errors}" type="text" v-model="form.name" @keydown.enter="edit">
            <div class="invalid-feedback" v-text="'name' in errors ? errors.abbreviation[0] : ''"></div>
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-secondary" title="Bearbeiten" @click="edit"><i class="fas fa-fw fa-save pointer"></i></button>
                <button type="button" class="btn btn-secondary" title="Bearbeiten" @click="isEditing = false"><i class="fas fa-fw fa-times pointer"></i></button>
            </div>
        </div>
        <div class="col d-flex align-items-center justify-content-between px-0" style="height: 40px;" v-else>
            <a class="m-0 text-muted" style="font-size: 16px;">{{ item.name }}</a>
            <div class="dropdown">
                <button class="btn btn-link dropdown-toggle text-body" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-fw fa-ellipsis-h"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#" @click="editing">Umbennen</a>
                    <a class="dropdown-item" href="#" v-if="items_count === 0" @click="destroy()">Löschen</a>
                </div>
            </div>
        </div>

        <tasks :project-section-id="item.id" @fetched="items_count = $event"></tasks>

    </div>

</template>

<script>
    import tasks from '../todo/index.vue';

    export default {

        components: {
            tasks
        },

        props: [
            'item',
            'shouldEdit',
        ],

        data() {
            return {
                id: this.item.id,
                form: {
                    name: this.item.name,
                },
                isEditing: this.shouldEdit,
                errors: {},
                items_count: null,
            };
        },

        methods: {
            editing() {
                this.isEditing = true;
            },
            edit() {
                var component = this;
                axios.put(component.item.path, component.form)
                    .then( function (response) {
                        component.$emit('updated', response.data);
                        Vue.success('Projektabschnitt gespeichert.');
                        component.isEditing = false;
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                });

            },
            destroy() {
                var component = this;
                axios.delete('/projektabschnitte/' + component.id)
                    .then( function (response) {
                        component.$emit('deleted', component.id);
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                });
            },
        },
    };
</script>