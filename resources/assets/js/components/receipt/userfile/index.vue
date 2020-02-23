<template>
    <div v-if="file == null" style="width: 100%; height: 100%;">
        <div class="card text-muted">
            <form enctype="multipart/form-data">
                <input type="file" multiple :disabled="isCreating" @change="changed($event.target.files);" class="input-file">
                <p v-if="isCreating">
                    Lade PDF hoch...
                </p>
                <p v-else>
                    PDF hier ablegen, <br />oder klicken
                </p>
            </form>
        </div>
        <div class="invalid-feedback" v-text="error" :style="{display: (error.length > 0 ? 'block' : 'none')}"></div>
    </div>
    <div class="flex-grow-1" v-else>
        <object :data="file.url" style="width: 100%; height: 600px">
            <center>PDF kann nicht angezeigt werden.</center>
        </object>
        <button class="btn btn-secondary" @click="destroy"><i class="fas fa-fw fa-trash"></i></button>
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

        computed: {
            error() {
                var first;
                for (var i in this.errors) {
                    first = this.errors[i];
                    break;
                }

                if (first) {
                    return first[0];
                }

                return '';
            },
        },

        data() {
            return {
                isCreating: false,
                file: this.model.preview_file,
                errors: {},
                files_count: null,
            };
        },

        methods: {
            changed (files) {
                this.errors = {};
                const formData = new FormData();

                this.files_count = files.length

                if (this.files_count == 0) {
                    return;
                }

                Array
                    .from(Array(this.files_count).keys())
                    .map(x => {
                        formData.append('files[]', files[x], files[x].name);
                });

                this.create(formData);
            },
            create (formData) {
                var component = this;
                component.isCreating = true;
                axios.post(component.model.path.replace('/edit', '') + '/dateien', formData)
                    .then(function (response) {
                        component.errors = {};
                        component.file = response.data[0];
                        component.$emit('created', response.data);
                        component.isCreating = false;
                        Vue.success('Datei hochgeladen');
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                        component.isCreating = false;
                });
            },
            destroy() {
                var component = this;
                axios.delete('/dateien/' + this.file.id)
                    .then( function (response) {
                        component.file = null;
                        Vue.success('Datei gelöscht');
                    });
            },
        },

    };
</script>

<style scoped>

    form {
        height: 100%;
    }

    .card {
        height: 100px;
        position: relative;
        cursor: pointer;
    }

    .card p {
        font-size: 1.2em;
        text-align: center;
        padding: 25px 0;
    }

    .input-file {
        opacity: 0;
        width: 100%;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        cursor: pointer;
      }
</style>