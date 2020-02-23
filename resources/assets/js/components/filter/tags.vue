<template>
    <div class="col-md-3" v-show="options.length">
        <label class="typo__label">Tags</label>
        <multiselect v-model="value" label="name" track-by="id" @input="input" :options="options" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true"></multiselect>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect'

    export default {

        props: [
            'initialValue',
            'options',
        ],

        components: { Multiselect },

        data () {
            return {
                value: [],
            };
        },

        methods: {
            input(value) {
                var input = [];
                value.forEach( function (item, index) {
                    input.push(item.name);
                });
                this.$emit('input', input);
            },
            setInitialValue() {
                var component = this;
                var initial = [];
                component.options.forEach( function (option, index) {
                    if (component.initialValue.indexOf(option.name) != -1) {
                        initial.push(option);
                    }
                });
                return initial;
            },
        },
    };
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>