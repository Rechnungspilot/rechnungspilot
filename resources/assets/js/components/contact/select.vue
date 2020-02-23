<template>
    <div>
        <label class="typo__label" v-show="label">Tags</label>
        <multiselect v-model="value" track-by="id" label="name" :options="all" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" @select="create($event)" @remove="destroy($event)" @input="$emit('input', value)">

        </multiselect>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect'

    export default {

        props: [ 'selected', 'showLabel' ],

        components: { Multiselect },
        data () {
            return {
                value: this.selected,
                all: [],
                label: this.showLabel === undefined ? true : this.showLabel,
            }
        },

        beforeMount() {
            this.fetchAll();
        },

        methods: {
            fetchAll() {
                var component = this;
                axios.get('/kontakte')
                    .then( function (response) {
                        component.all = response.data;
                });
            },
            create (tag) {
                var component = this;
                axios.post('/' + component.type + '/' + component.type_id + '/tags/' + tag.id)
                    .then(function (response) {

                });
            },
            destroy (tag) {
                var component = this;
                axios.delete('/' + component.type + '/' + component.type_id + '/tags/' + tag.id)
                    .then(function (response) {

                });
            },
        },
    };
</script>