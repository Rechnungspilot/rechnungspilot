<template>
    <tr v-if="edit">
        <td class="align-middle pointer">
            <input class="form-control" :class="'name' in errors ? 'is-invalid' : ''" type="text" v-model="name">
            <div class="invalid-feedback" v-text="'name' in errors ? errors.name[0] : ''"></div>
        </td>
        <td class="align-middle pointer">
            <input class="form-control" :class="'abbreviation' in errors ? 'is-invalid' : ''" type="text" v-model="abbreviation">
            <div class="invalid-feedback" v-text="'abbreviation' in errors ? errors.abbreviation[0] : ''"></div>
        </td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-primary" title="Speichern" @click="update"><i class="fas fa-fw fa-save"></i></button>
                <button type="button" class="btn btn-secondary" title="Abbrechen" @click="edit = false"><i class="fas fa-fw fa-times"></i></button>
            </div>
        </td>
    </tr>
    <tr v-else>
        <td class="align-middle pointer" @click="edit = true">{{ name }}</td>
        <td class="align-middle pointer" @click="edit = true">{{ abbreviation }}</td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-secondary" title="Bearbeiten" @click="edit = true"><i class="fas fa-fw fa-edit"></i></button>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy"><i class="fas fa-fw fa-trash"></i></button>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {

        props: [ 'item', 'uri' ],

        data () {
            return {
                id: this.item.id,
                type: this.item.type,
                edit: false,
                name: this.item.name,
                abbreviation: this.item.abbreviation,
                errors: {},
            };
        },

        methods: {
            destroy() {
                axios.delete(this.uri + '/' + this.id);
                this.$emit("deleted", this.id);
            },
            update() {
                var component = this;
                axios.put(this.uri + '/' + this.id, {
                    name: this.name,
                    abbreviation: this.abbreviation,
                })
                    .then( function (response) {
                        component.errors = {};
                        component.edit = false;
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                });
            },
        },
    };
</script>