<template>
    <tr v-if="edit">
        <td class="align-middle pointer">
            <input class="form-control" :class="'name' in errors ? 'is-invalid' : ''" type="text" v-model="name">
            <div class="invalid-feedback" v-text="'name' in errors ? errors.name[0] : ''"></div>
        </td>
        <td class="align-middle pointer">
            <select class="form-control" v-model="placeholderKey" @change="appendPlaceholder">
                <option value="0">Platzhalter</option>
                <option v-for="(option, key) in placeholder" :value="key">{{ option }}</option>
            </select>
            <wysiwyg v-model="text" />
            <div class="invalid-feedback" v-text="'text' in errors ? errors.text[0] : ''"></div>
        </td>
        <td class="align-middle pointer">
            <select class="form-control" :class="'standard' in errors ? 'is-invalid' : ''" v-model="standard">
                <option v-for="(option, key) in standards" :value="key">{{ option }}</option>
            </select>
            <div class="invalid-feedback" v-text="'standard' in errors ? errors.standard[0] : ''"></div>
        </td>
        <td class="align-middle text-right">
            <button type="button" class="btn btn-primary" title="Speichern" @click="update"><i class="fas fa-save"></i></button>
            <button type="button" class="btn btn-secondary" title="Abbrechen" @click="edit = false"><i class="fas fa-times"></i></button>
        </td>
    </tr>
    <tr v-else>
        <td class="align-middle pointer">{{ name }}</td>
        <td class="align-middle pointer" v-html="text"></td>
        <td class="align-middle pointer">{{ standardName }}</td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-secondary" title="Bearbeiten" @click="edit = true"><i class="fas fa-edit"></i></button>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy"><i class="fas fa-trash"></i></button>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {

        props: [
            'item',
            'placeholder',
            'standards',
            'uri',
        ],

        data () {
            return {
                id: this.item.id,
                edit: false,
                name: this.item.name,
                text: this.item.text,
                standard: this.item.standard,
                standardName: this.item.standardName,
                placeholderKey: 0,
                errors: {},
            };
        },

        methods: {
            appendPlaceholder() {
                if (this.placeholderKey == 0) return;
                this.text = this.text + ' ' + this.placeholderKey + '';
                this.placeholderKey = 0;
            },
            destroy() {
                axios.delete(this.uri + '/' + this.id);
                this.$emit("deleted", this.id);
            },
            update() {
                var component = this;
                axios.put(this.uri + '/' + this.id, {
                    name: this.name,
                    text: this.text,
                    standard: this.standard,
                })
                    .then( function (response) {
                        component.errors = {};
                        component.standardName = response.data.standardName;
                        component.edit = false;
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                });
            },
        },
    };
</script>