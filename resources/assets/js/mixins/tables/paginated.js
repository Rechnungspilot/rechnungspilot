export const paginatedMixin = {

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
                page: 1,
                show: false,
                searchtext: '',
            },
            form: {

            },
            isLoading: true,
            items: [],
            paginate: {
                nextPageUrl: null,
                prevPageUrl: null,
                lastPage: 0,
                currentPage: 0,
            },
            selected: [],
        };
    },

    mounted() {

        this.fetch();

    },

    watch: {
        page () {
            this.fetch();
        },
    },

    computed: {
        page() {
            return this.filter.page;
        },
    },

    methods: {
        create() {
            var component = this;
            axios.post(this.indexPath, component.form)
                .then(function (response) {
                    component.resetForm();
                    Vue.successCreate(response.data);
                    location.href = response.data.edit_path;
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
                    component.items = response.data.data;
                    component.filter.page = response.data.current_page;
                    component.paginate.nextPageUrl = response.data.next_page_url;
                    component.paginate.prevPageUrl = response.data.prev_page_url;
                    component.paginate.lastPage = response.data.last_page;
                    component.paginate.currentPage = response.data.current_page;
                    component.isLoading = false;
                })
                .catch(function (error) {
                    console.log(error);
                    Vue.error('Datensätze konnten nicht geladen werden.');
                });
        },
        hasFilter() {
            return (Object.keys(this.filter).length > 3);
        },
        searching(searchtext) {
            this.filter.searchtext = searchtext;
            this.search();
        },
        search() {
            this.filter.page = 1;
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