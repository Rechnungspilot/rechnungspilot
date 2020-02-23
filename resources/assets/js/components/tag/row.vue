<template>
    <tr v-if="isEditing">
        <td class="align-middle pointer">
            <input class="form-control" :class="'name' in errors ? 'is-invalid' : ''" type="text" v-model="form.name">
            <div class="invalid-feedback" v-text="'name' in errors ? errors.name[0] : ''"></div>
        </td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-primary" title="Speichern" @click="update"><i class="fas fa-fw fa-save"></i></button>
                <button type="button" class="btn btn-secondary" title="Abbrechen" @click="form.name = tag.name; isEditing = false;"><i class="fas fa-fw fa-times"></i></button>
            </div>
        </td>
    </tr>
    <tr v-else>
        <td class="align-middle pointer" @click="isEditing = true">{{ form.name }}</td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-secondary" title="Bearbeiten" @click="isEditing = true"><i class="fas fa-fw fa-edit"></i></button>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy"><i class="fas fa-fw fa-trash"></i></button>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {

        props: [ 'tag' ],

        data () {
            return {
                id: this.tag.id,
                type: this.tag.type,
                isEditing: false,
                form: {
                    name: this.tag.name,
                },
                errors: {},
                uri: '/kategorien/' + this.tag.id
            };
        },

        methods: {
            destroy() {
                axios.delete(this.uri);
                this.$emit("deleted", this.id);
            },
            update() {
                var component = this;
                axios.put(this.uri, component.form)
                    .then( function (response) {
                        component.errors = {};
                        component.isEditing = false;
                })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                });
            },
        },
    };
</script>