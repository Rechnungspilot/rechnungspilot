<template>
    <div>
        <multiselect v-model="item" :custom-label="label" track-by="id" :options="items" :multiple="false" :close-on-select="true" :clear-on-select="false" placeholder="suchen.." :name="name" :loading="isLoading" :preserve-search="true" @search-change="search" @input="$emit('input', item)"></multiselect>
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
            name: {
                type: String,
                default: null,
            }
        },

        watch: {
            value(newValue) {
                this.item = newValue;
                if (newValue) {
                    this.items = [];
                    this.items.push(newValue);
                }
            },
        },

        data() {
            return {
                isLoading: false,
                item: this.value,
                items: this.value ? [this.value] : [],
            };
        },

        methods: {
            search(searchtext) {
                var component = this;
                if (component.searchTimeout)
                {
                    clearTimeout(component.searchTimeout);
                    component.searchTimeout = null;
                }
                component.isLoading = true;
                component.searchTimeout = setTimeout(function () {
                    axios.get('/raw/kontakte', {
                        params: {
                            searchtext,
                        }
                    })
                    .then(function (response) {
                        component.items = response.data;
                        component.isLoading = false;
                    });
                }, 300);
            },
            label({ number, name }) {
                return `${number} ${name}`;
            },
        },
    };
</script>