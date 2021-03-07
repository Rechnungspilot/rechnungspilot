export const baseMixin = {

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
                    Vue.error('Datensätze konnten nicht geholt werden.');
                });
        },
        hasFilter() {
            return (Object.keys(this.filter).length > 2);
        },
        search(searchtext) {
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