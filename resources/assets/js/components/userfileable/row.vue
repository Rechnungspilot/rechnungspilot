<template>
    <tr v-if="edit">
        <td class="align-middle">
            <input class="form-control" :class="'name' in errors ? 'is-invalid' : ''" type="text" v-model="name" @keydown.enter="update">
            <div class="invalid-feedback" v-text="'name' in errors ? errors.name[0] : ''"></div>
        </td>
        <td class="align-middle">
            <tag-select v-model="tags" :selected="tags" type="dateien" :type_id="id" :showLabel="false"></tag-select>
        </td>
        <td class="text-right align-middle">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-primary" title="Speichern" @click="update"><i class="fas fa-fw fa-save"></i></button>
                <button type="button" class="btn btn-secondary" title="Abbrechen" @click="edit = false"><i class="fas fa-fw fa-times"></i></button>
            </div>
        </td>
    </tr>
    <tr v-else>
        <td class="align-middle pointer" @click="link">
            {{ item.name }}<br />
            <span class="text-muted">{{ item.original_name }}</span>
        </td>
        <td class="align-middle pointer">
            {{ tagsString }}
        </td>
        <td class="text-right align-middle">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-secondary" title="Bearbeiten" @click="edit = true"><i class="fas fa-fw fa-edit"></i></button>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy"><i class="fas fa-fw fa-trash"></i></button>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {

        props: [
            'item',
            'uri',
        ],

        data () {
            return {
                id: this.item.id,
                edit: false,
                name: this.item.name,
                tags: this.item.tags,
                errors: {},
            };
        },

        computed: {
            tagsString() {
                return this.tags.map( function (tag) {
                    return tag.name;
                }).join(', ');
            },
        },

        methods: {
            destroy() {
                axios.delete('/dateien/' + this.id);
                this.$emit("deleted", this.id);
            },
            update() {
                var component = this;
                axios.put('/dateien/' + this.id, {
                    name: this.name,
                })
                    .then( function (response) {
                        component.errors = {};
                        component.edit = false;
                        component.$emit('updated', response.data);
                })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                });
            },
            link() {
                location.href = this.item.url;
            },
        },
    };
</script>