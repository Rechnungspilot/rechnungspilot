<template>
    <div class="col px-0">
        <div class="card text-muted">
            <form enctype="multipart/form-data">
                <input type="file" multiple :disabled="isCreating" @change="changed($event.target.files);" class="input-file">
                <p v-if="isCreating">
                    Lade {{ files_count }} {{ files_count == 1 ? 'Datei' : 'Dateien' }} hoch...
                </p>
                <p v-else>
                    Dateien hier ablegen, <br />oder klicken
                </p>
            </form>
        </div>
        <div class="invalid-feedback" v-text="error" :style="{display: (error.length > 0 ? 'block' : 'none')}"></div>
    </div>
</template>

<script>
    export default {

        props: {
            model: {
                default: null,
                required: false,
            }
        },

        data() {
            return {
                isInitial: true,
                isCreating: false,
                uri: (this.model !== null ? this.model.path.replace('/edit', '') : '') + '/dateien',
                errors: {},
                files_count: null,
            };
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

        methods: {
            changed (files) {
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
                axios.post(component.uri, formData)
                    .then(function (response) {
                        component.errors = {};
                        component.$emit('created', response.data);
                        component.isCreating = false;
                })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                        component.isCreating = false;
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