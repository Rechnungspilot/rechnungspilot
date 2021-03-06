<template>
    <table-base :is-loading="isLoading" :items-length="items.length" :has-filter="true" @searching="search($event)" @creating="create">

        <template v-slot:form>
            <div class="form-group mb-0 mr-1">
                <input-text v-model="form.name" placeholder="Name" :error="error('name')" @keydown.enter="create"></input-text>
            </div>
            <div class="form-group mb-0 mr-1">
                <input-text v-model="form.abbreviation" placeholder="Abkürzung" :error="error('abbreviation')" @keydown.enter="create"></input-text>
            </div>
        </template>

        <template v-slot:thead>
            <tr>
                <th width="80%">Bezeichnung</th>
                <th class="d-none d-sm-table-cell" width="20%">Abkürzung</th>
                <th class="text-right" width="100">Aktion</th>
            </tr>
        </template>

        <template v-slot:tbody>
            <row :item="item" :key="item.id" v-for="(item, index) in items" @deleted="deleted(index)" @updated="updated(index, $event)"></row>
        </template>

    </table-base>

</template>

<script>
    import row from './row.vue';
    import inputText from '../../form/input/text.vue';
    import tableBase from '../../tables/base.vue';

    export default {

        components: {
            row,
            inputText,
            tableBase,
        },

        props: {
            indexPath: {
                type: String,
                required: true,
            }
        },

        data () {
            return {
                errors: {},
                filter: {
                    show: false,
                    searchtext: '',
                },
                form: {
                    name: '',
                    abbreviation: '',
                },
                isLoading: true,
                items: [],
            };
        },

        mounted() {

            this.fetch();

        },

        methods: {
            create() {
                var component = this;
                axios.post(this.indexPath, component.form)
                    .then(function (response) {
                        component.resetForm();
                        component.items.unshift(response.data);
                        Vue.successCreate(response.data);
                })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                        Vue.errorCreate();
                });
            },
            resetErrors() {
                this.errors = {};
            },
            resetForm() {
                this.resetErrors();
                for (var index in this.form) {
                    this.form[index] = '';
                }
            },
            error(name) {
                return (name in this.errors ? this.errors[name][0] : '');
            },
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get(component.indexPath, {
                    params: component.filter
                })
                    .then(function (response) {
                        component.items = response.data;
                        component.isLoading = false;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            search (searchtext) {
                this.filter.searchtext = searchtext;
                this.fetch();
            },
            deleted(index) {
                var item = this.items[index];
                this.items.splice(index, 1);
                Vue.successDelete(item);
            },
            updated(index, item) {
                Vue.set(this.items, index, item);
                Vue.successUpdate(item);
            },
        },
    };
</script>