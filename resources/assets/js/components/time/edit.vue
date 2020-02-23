<template>
    <div class="modal fade" tabindex="-1" role="dialog" id="time-edit">
        <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Zeiten {{ model !== null ? 'bearbeiten' : 'anlegen' }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <team-input :options="team" :forceTeam="true" v-model="form.user_id"></team-input>

                        <div class="form-group">
                            <label>Dienstleistung</label>
                            <service-input v-model="item" :error="'item_id' in errors ? errors.item_id[0] : ''"></service-input>
                        </div>

                        <div class="form-row align-items-end">
                            <div class="col-auto form-group">
                                <label>Start</label>
                                <calendar-date v-model="form.start_at" :error="'start_at' in errors ? errors.start_at[0] : ''"></calendar-date>
                            </div>
                            <div class="col-auto form-group">
                                <timepicker v-model="form.start_at" :error="'start_at' in errors ? errors.start_at[0] : ''"></timepicker>
                            </div>
                            <div class="col"></div>
                        </div>

                        <div class="form-row align-items-end">
                            <div class="col-auto form-group">
                                <label>Ende</label>
                                <calendar-date v-model="form.end_at" :error="'end_at' in errors ? errors.end_at[0] : ''"></calendar-date>
                            </div>
                            <div class="col-auto form-group">
                                <timepicker v-model="form.end_at" :error="'end_at' in errors ? errors.end_at[0] : ''"></timepicker>
                            </div>
                            <div class="col"></div>
                        </div>

                        <div class="form-group">
                            <label>Dauer</label>
                            <input class="form-control" type="text" v-model="formatedDuration" readonly="readonly">
                        </div>

                        <div class="form-group">
                            <label>Aufgabe oder Termin (optional)</label>
                            <multiselect v-model="todo" :custom-label="labelTodo" track-by="id" :options="todos" :multiple="false" :close-on-select="true" :clear-on-select="false" placeholder="suchen.." :loading="isLoadingTodos" :preserve-search="true" @search-change="searchTodos" @input="todo == null ? form.todo_id = 0 : form.todo_id = todo.id; clearError('todo_id');"></multiselect>
                            <div class="invalid-feedback" v-text="'order_id' in errors ? errors.order_id[0] : ''" :style="{display: 'order_id' in errors ? 'block' : 'none'}"></div>
                        </div>

                        <div class="form-group">
                            <label><b>Notiz</b></label>
                            <textarea class="form-control" v-model="form.note"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" @click="model !== null ? edit() : create()">Speichern</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import moment from "moment";
    import Multiselect from 'vue-multiselect';

    import calendarDate from '../form/input/calendar-date.vue';
    import datetime from "../form/input/datetime.vue";
    import serviceInput from '../form/input/item/service.vue';
    import teamInput from "../form/input/team.vue";
    import timepicker from '../form/input/time.vue';

    export default {

        components: {
            calendarDate,
            datetime,
            Multiselect,
            serviceInput,
            teamInput,
            timepicker,
        },

        props: [
            'time',
            'team',
            'model',
        ],

        data() {
            return {
                uri: '/zeiten',
                form: {
                    start_at: moment().format(),
                    end_at: moment().format(),
                    user_id: this.team[0].id,
                    item_id: 0,
                    todo_id: 0,
                    note: '',
                },
                isLoadingTodos: false,
                item: null,
                todo: null,
                items: [],
                todos: [],
                searchTimeout: null,
                duration: 0,
                errors: {},
            };
        },

        watch: {
            model (newValue, oldValue) {
                this.items = [];
                if (newValue === null) {
                    this.form = {
                        start_at: moment().format(),
                        end_at: moment().format(),
                        user_id: this.team[0].id,
                        item_id: 0,
                        todo_id: 0,
                        note: '',
                    };
                    this.item = null;
                    this.todo = null;
                }
                else {
                    this.form = {
                        start_at: moment(newValue.start_at).format(),
                        end_at: moment(newValue.end_at).format(),
                        user_id: newValue.user_id,
                        item_id: newValue.item_id,
                        todo_id: newValue.timeable_id ? newValue.timeable_id : 0,
                        note: newValue.note,
                    };
                    this.item = newValue.item;
                    this.todo = newValue.timeable;
                    this.items.push(newValue.item);
                    if (this.todo !== null) {
                        this.todos.push(newValue.timeable);
                    }
                }
                this.setDuration();
            },
            item(newValue) {
                this.form.item_id = newValue ? newValue.id : 0;
                if (newValue) {
                    if (this.form.name == '') {
                        this.form.name = newValue.name;
                    }
                    if (newValue.duration > 0) {
                        this.duration = newValue.duration;
                        this.form.end_at = moment(this.start_at).add(this.duration, 'seconds').format();
                    }
                }
            },
            end_at(newValue, oldValue) {
                this.setDuration();
            },
            start_at(newValue, oldValue) {
                this.setDuration();
            },
        },

        computed: {
            start_at() {
                return this.form.start_at;
            },
            end_at() {
                return this.form.end_at;
            },
            formatedDuration() {
                if (this.duration == 0) {
                    return '00:00';
                }
                var hours = Math.floor(this.duration / 3600);
                var mins  = Math.floor((this.duration / 60) % 60);
                var pad = '00';

                return (pad + hours).slice(-2) + ':' + (pad + mins).slice(-2);
            },
        },

        methods: {
            create() {
                var component = this;
                axios.post(component.uri, component.form)
                    .then(function (response) {
                        component.errors = {};
                        component.form = {
                            start_at: moment().format(),
                            end_at: moment().format(),
                            user_id: component.team[0].id,
                            item_id: 0,
                            todo_id: 0,
                            note: '',
                        };
                        component.item = null;
                        component.$emit('created', response.data);
                        $('#time-edit').modal('hide');
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                });
            },
            edit() {
                var component = this;
                axios.put(component.uri + '/' + component.model.id, component.form)
                    .then(function (response) {
                        component.errors = {};
                        component.name = '';
                        component.$emit('updated', response.data);
                        $('#time-edit').modal('hide');
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                });
            },
            setDuration() {
                this.duration = moment.duration(moment(this.form.end_at).diff(moment(this.form.start_at))).asSeconds();
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