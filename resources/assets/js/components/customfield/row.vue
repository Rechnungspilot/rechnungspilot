<template>
    <tr v-if="isEditing">
        <td class="align-middle pointer">
            <input class="form-control" :class="'name' in errors ? 'is-invalid' : ''" type="text" v-model="form.name">
            <div class="invalid-feedback" v-text="'name' in errors ? errors.name[0] : ''"></div>
        </td>
        <td class="align-middle pointer">
            <select class="form-control" v-model="form.input_type">
                <option :value="index" v-for="(type, index) in inputTypes">{{ type }}</option>
            </select>
            <div class="invalid-feedback" v-text="'name' in errors ? errors.name[0] : ''"></div>
        </td>
        <td class="align-middle pointer">
            <div class="form-check">
                <input class="form-check-input pointer" :id="'default-' + id" type="checkbox" v-model="form.default">
                <label class="form-check-label" :for="'default-' + id"></label>
            </div>
        </td>
        <td class="align-middle pointer">
            <div v-show="form.input_type == 'select'">
                <textarea class="form-control" rows="3" v-model="form.options"></textarea>
                <small>Eine Option pro Zeile</small>
            </div>
        </td>
        <td class="align-middle pointer">
            <textarea class="form-control" rows="3" v-model="form.info"></textarea>
        </td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-primary" title="Speichern" @click="update"><i class="fas fa-fw fa-save"></i></button>
                <button type="button" class="btn btn-secondary" title="Abbrechen" @click="isEditing = false"><i class="fas fa-fw fa-times"></i></button>
            </div>
        </td>
    </tr>
    <tr v-else>
        <td class="align-middle pointer" @click="isEditing = true">{{ form.name }}</td>
        <td class="align-middle pointer" @click="isEditing = true">{{ inputTypes[form.input_type] }}</td>
        <td class="align-middle pointer" @click="isEditing = true">
            <i class="fas fa-fw fa-check" v-if="form.default == true"></i>
            <i class="fas fa-fw fa-times" v-else></i>
        </td>
        <td class="align-middle pointer" @click="isEditing = true">{{ optionsCommaSeperated }}</td>
        <td class="align-middle pointer" @click="isEditing = true">{{ form.info }}</td>
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

        props: [
            'item',
            'inputTypes',
        ],

        data () {
            return {
                id: this.item.id,
                isEditing: false,
                form: {
                    name: this.item.name,
                    input_type: this.item.input_type,
                    default: this.item.default,
                    options: this.item.optionsAsString,
                    info: this.item.info,
                },
                errors: {},
            };
        },

        computed: {
            optionsCommaSeperated() {
                return this.form.options ? this.form.options.replace(/\n/g, ', ') : '';
            }
        },

        methods: {
            destroy() {
                var component = this;
                axios.delete('/felder/' + component.id)
                    .then( function (response) {
                        if (response.data.deleted) {
                            component.$emit("deleted", component.id);
                            Vue.success('Feld wurde gelöscht.');
                        }
                        else {
                            Vue.error('Feld konnte nicht gelöscht werden. Es wurde bereits verwendet.');
                        }
                    });

            },
            update() {
                var component = this;
                axios.put('/felder/' + this.id, this.form)
                    .then( function (response) {
                        component.errors = {};
                        component.isEditing = false;
                        Vue.success('Änderungen gespeichert.');
                })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                });
            },
        },
    };
</script>