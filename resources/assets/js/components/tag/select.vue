<template>
    <div class="form-group row" v-if="allTags.length">
        <label class="col-sm-4 col-form-label col-form-label-sm" v-show="label">Kategorien</label>
        <div class="col-sm-8">
            <multiselect v-model="value" track-by="id" label="name" :options="allTags" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" @select="create($event)" @remove="destroy($event)" @input="$emit('input', value)"></multiselect>
        </div>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect'

    export default {

        props: {
            selected: {
                required: true,
            },
            indexPath: {
                type: String,
                required: true,
            },
            path: {
                type: String,
                required: true,
            },
            showLabel: {
                type: Boolean,
                required: false,
                default: true,
            },
        },

        components: { Multiselect },
        data () {
            return {
                value: this.selected,
                allTags: [],
                label: this.showLabel === undefined ? true : this.showLabel,
            }
        },

        beforeMount() {
            this.fetchAllTags();
        },

        watch: {
            selected(newValue, oldValue) {
                this.value = newValue;
            },
        },

        methods: {
            fetchAllTags() {
                var component = this;
                axios.get(component.indexPath)
                    .then( function (response) {
                        component.allTags = response.data;
                });
            },
            create (tag) {
                var component = this;
                axios.post(component.path + '/' + tag.id)
                    .then(function (response) {

                });
            },
            destroy (tag) {
                var component = this;
                axios.delete(component.path + '/' + tag.id)
                    .then(function (response) {

                });
            },
        },
    };
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>