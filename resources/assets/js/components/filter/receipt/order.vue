<template>
    <div>
        <multiselect v-model="item" :custom-label="label" track-by="id" :options="items" :multiple="false" :close-on-select="true" :clear-on-select="false" placeholder="Auftrag wählen" :loading="isLoading" :preserve-search="true" @input="$emit('input', $event)"></multiselect>
        <div class="invalid-feedback" v-text="error" :style="{display: error ? 'block' : 'none'}"></div>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';

    export default {
        components: {
            Multiselect,
        },

        props: {
            error: {
                type: String,
                default: '',
            },
            value: {
                type: Object,
                default: null,
            },
        },

        watch: {
            value(newValue) {
                this.item = newValue;
            },
        },

        data() {
            return {
                isLoading: false,
                item: this.value,
                items: this.value ? [this.value] : [],
            };
        },

        mounted() {
            this.fetch();
        },

        methods: {
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get('/raw/auftraege')
                    .then( function (response) {
                        component.items = response.data;
                        component.isLoading = false;
                });
            },
            search(searchtext) {
                var component = this;
                if (component.searchTimeout)
                {
                    clearTimeout(component.searchTimeout);
                    component.searchTimeout = null;
                }
                component.isLoading = true;
                component.searchTimeout = setTimeout(function () {
                    axios.get('/raw/auftraege', {
                        params: {
                            searchtext,
                            type: 1,
                        }
                    })
                    .then(function (response) {
                        component.items = response.data;
                        component.isLoading = false;
                    });
                }, 300);
            },
            label({ typeName, name }) {
                return (typeName ? typeName + ' ' : '') + name;
            },
        },
    };
</script>