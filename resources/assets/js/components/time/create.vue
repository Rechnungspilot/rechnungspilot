<template>
    <div class="card">

        <div class="card-body">

            <div class="mb-3" v-show="creatingTodo">
                <create-todo :item-id="form.item_id" @created="createdTodo" @canceled="creatingTodo = false;"></create-todo>
            </div>

            <div class="form-group">
                <label class="d-flex justify-content-between">
                    <div>Aufgabe (optional)</div>
                    <div class="d-flex">
                        <i class="fas fa-fw fa-sync pointer" @click="fetchTodos" v-show="isLoadingTodos == false"></i>
                        <i class="fas fa-fw fa-plus-square pointer ml-1" @click="creatingTodo = true;" v-show="creatingTodo == false"></i>
                    </div>
                </label>
                <multiselect v-model="todo" :custom-label="labelTodo" track-by="id" :options="todos" :multiple="false" :close-on-select="true" :clear-on-select="false" placeholder="suchen.." :loading="isLoadingTodos" :preserve-search="true" @input="changedTodo($event)">
                    <template slot="option" slot-scope="props">
                        <div class="option__desc">
                            <div class="option__title mb-1">{{ props.option.name }}</div>
                            <div class="option__small text-muted" v-if="props.option.todoable">{{ props.option.fullName }}</div>
                        </div>
                    </template>
                </multiselect>
                <div class="invalid-feedback" v-text="'order_id' in errors ? errors.order_id[0] : ''" :style="{display: 'order_id' in errors ? 'block' : 'none'}"></div>
            </div>

            <div class="form-group">
                <label>Dienstleistung</label>
                <service-input v-model="item" :error="'item_id' in errors ? errors.item_id[0] : ''" @input="item == null ? form.item_id = 0 : form.item_id = item.id; clearError('item_id');"></service-input>
            </div>

        </div>

        <div class="card-footer">
            <button type="button" class="btn btn-primary" @click="create()">Starten</button>
        </div>

    </div>
</template>

<script>
    import moment from "moment";
    import Multiselect from 'vue-multiselect';
    import serviceInput from '../form/input/item/service.vue';
    import createTodo from './todo/create.vue';

    export default {

        components: {
            createTodo,
            Multiselect,
            serviceInput,
        },

        props: [
            'userId'
        ],

        data() {
            return {
                creatingTodo: false,
                uri: '/zeiterfassung',
                form: {
                    user_id: this.userId,
                    item_id: 0,
                    todo_id: 0,
                },
                formTodo: {
                    name: '',
                },
                isLoadingTodos: false,
                item: null,
                items: [],
                todo: null,
                todos: [],
                searchTimeout: null,
                errors: {},
            };
        },

        mounted() {
            this.fetchTodos();
        },

        methods: {
            create() {
                var component = this;
                axios.post(component.uri, component.form)
                    .then(function (response) {
                        component.errors = {};
                        component.name = '';
                        component.$emit('created', response.data);
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                });
            },
            changedTodo(todo) {
                this.form.todo_id = (todo == null ? 0 : todo.id);
                if (this.form.todo_id > 0 && this.form.item_id == 0) {
                    this.form.item_id = todo.item_id;
                    this.item = todo.item;
                }
                this.clearError('todo_id');
            },
            createdTodo(todo) {
                this.todos.push(todo);
                this.todo = todo;
                this.changedTodo(todo);
                this.creatingTodo = false;
            },
            fetchTodos() {
                var component = this;
                component.isLoadingTodos = true;
                axios.get('/raw/aufgaben', {
                    params: {
                        auth_users: true
                    },
                })
                    .then(function (response) {
                        component.todos = response.data;
                        component.isLoadingTodos = false;
                });
            },
            searchTodos(searchtext) {
                var component = this;
                if (component.searchTimeout)
                {
                    clearTimeout(component.searchTimeout);
                    component.searchTimeout = null;
                }
                component.isLoadingTodos = true;
                component.searchTimeout = setTimeout(function () {
                    axios.get('/raw/aufgaben' + '?searchtext=' + searchtext)
                    .then(function (response) {
                        component.todos = response.data;
                        component.isLoadingTodos = false;
                    });
                }, 300);
            },
            labelTodo({ name }) {
                return `${name}`;
            },
            clearError(key) {
                delete this.errors[key];
            }
        },

    };
</script>