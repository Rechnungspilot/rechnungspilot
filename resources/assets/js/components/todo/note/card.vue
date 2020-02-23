<template>
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Notiz</span>
            <button class="btn btn-link text-body" @click="isEditing = true" v-show="isEditing == false"><i class="fas fa-fw fa-edit"></i></button>
        </div>
        <div class="card-body">
            <textarea class="form-control" rows="10" v-model="form.note" v-if="isEditing">{{ form.note }}</textarea>
            <p class="whitespace-pre" v-else>{{ item.note }}</p>
        </div>
        <div class="card-footer text-right" v-show="isEditing">
            <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-secondary" title="Abbrechen" @click="cancel"><i class="fas fa-fw fa-times"></i></button>
                <button type="button" class="btn btn-primary" title="Speichern" @click="update"><i class="fas fa-fw fa-save"></i></button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        props: {
            model: {
                type: Object,
                required: true,
            },
        },

        data() {
            return {
                item: this.model,
                isEditing: false,
                form: {
                    note: this.model.note,
                },
            };
        },

        methods: {
            cancel() {
                this.form.note = this.item.note;
                this.isEditing = false;
            },
            update() {
                var component = this;
                axios.put(component.item.path, component.form)
                    .then( function (response) {
                        Vue.set(component, 'item', response.data);
                        Vue.success('Notiz gespeichert');
                        component.isEditing = false;
                    })
                    .catch (function (error) {
                        console.log(error);
                });
            },
        },

    };
</script>