<template>
    <tr v-if="isEditing">
        <td class="align-middle pointer">
            <input-text v-model="form.name" placeholder="Name" :error="error('name')" @keydown.enter="update"></input-text>
        </td>
        <td class="align-middle d-none d-sm-table-cell pointer">
            <input-text v-model="form.abbreviation" placeholder="Abkürzung" :error="error('abbreviation')" @keydown.enter="update"></input-text>
        </td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-primary" title="Speichern" @click="update"><i class="fas fa-fw fa-save"></i></button>
                <button type="button" class="btn btn-secondary" title="Abbrechen" @click="isEditing = false"><i class="fas fa-fw fa-times"></i></button>
            </div>
        </td>
    </tr>
    <tr v-else>
        <td class="align-middle pointer" @click="isEditing = true">{{ item.name }}</td>
        <td class="align-middle d-none d-sm-table-cell pointer" @click="isEditing = true">{{ item.abbreviation }}</td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-secondary" title="Bearbeiten" @click="isEditing = true"><i class="fas fa-fw fa-edit"></i></button>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy"><i class="fas fa-fw fa-trash"></i></button>
            </div>
        </td>
    </tr>
</template>

<script>
    import inputText from '../../form/input/text.vue';

    export default {

        components: {
            inputText
        },

        props: {
            item: {
                type: Object,
                required: true,
            },
        },

        data () {
            return {
                isEditing: false,
                form: {
                    name: this.item.name,
                    abbreviation: this.item.abbreviation,
                },
                errors: {},
            };
        },

        methods: {
            destroy() {
                axios.delete(this.item.path);
                this.$emit('deleted', this.item.id);
            },
            update() {
                var component = this;
                axios.put(component.item.path, component.form)
                    .then( function (response) {
                        component.errors = {};
                        component.isEditing = false;
                        component.$emit('updated', response.data);
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                });
            },
            error(name) {
                return (name in this.errors ? this.errors[name][0] : '');
            }
        },
    };
</script>