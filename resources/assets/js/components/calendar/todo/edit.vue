<template>
    <div class="modal fade" tabindex="-1" role="dialog" id="todo-edit">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Termin bearbeiten</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label>Dienstleistung</label>
                        <service-input v-model="item" :error="'item_id' in errors ? errors.item_id[0] : ''"></service-input>
                    </div>

                    <div class="form-group">
                        <label>Name</label>
                        <input v-model="form.name" class="form-control" :class="'name' in errors ? 'is-invalid' : ''" autofocus></input>
                        <div class="invalid-feedback" v-text="'name' in errors ? errors.name[0] : ''"></div>
                    </div>

                    <team-input v-model="form.user_id" :options="users"></team-input>

                    <div class="form-row align-items-end">
                        <div class=" col-auto form-group">
                            <label>Start</label>
                            <calendar-date v-model="form.start_at" :error="'start_at' in errors ? errors.start_at[0] : ''"></calendar-date>
                        </div>
                        <div class="col-auto form-group">
                            <timepicker v-model="form.start_at" :error="'start_at' in errors ? errors.start_at[0] : ''"></timepicker>
                        </div>
                        <div class="col"></div>
                    </div>

                    <div class="form-row align-items-end">
                        <div class=" col-auto form-group">
                            <label>Ende</label>
                            <calendar-date v-model="form.end_at" :error="'end_at' in errors ? errors.end_at[0] : ''"></calendar-date>
                        </div>
                        <div class="col-auto form-group">
                            <timepicker v-model="form.end_at" :error="'end_at' in errors ? errors.end_at[0] : ''"></timepicker>
                        </div>
                        <div class="col"></div>
                    </div>

                    <task-contacts-select :model="todo"></task-contacts-select>

                    <div class="form-group">
                        <label>Notiz</label>
                        <textarea class="form-control" v-model="form.note" rows="3"></textarea>
                    </div>

                </div>
                <div class="modal-footer d-flex align-items-center">
                    <div class="col">
                        <button class="btn btn-text text-muted px-0" @click.prevent="destroy()">Löschen</button>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary" @click.prevent="update">Speichern</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from "moment";
    import Multiselect from 'vue-multiselect';
    import calendarDate from '../../form/input/calendar-date.vue';
    import serviceInput from '../../form/input/item/service.vue';
    import teamInput from '../../form/input/team.vue';
    import timepicker from '../../form/input/time.vue';

    export default {

        components: {
            calendarDate,
            Multiselect,
            serviceInput,
            teamInput,
            timepicker,
        },

        props: {
            todo: {
                type: Object,
                default: null
            },
            users: {
                type: Array,
                required: true,
            },
        },

        watch: {
            todo(newValue, oldValue) {
                this.items = [];
                this.id = newValue.id;
                this.duration = newValue.duration;

                this.form.end_at = moment(newValue.end_at).format();
                this.form.start_at = moment(newValue.start_at).format();
                this.form.name = newValue.name;
                this.form.item_id = newValue.item_id;
                this.form.user_id = newValue.user_id;
                this.form.note = newValue.note;
                this.item = newValue.item;
                if (newValue.item_id) {
                    this.items.push(newValue.item)
                }
            },
            end_at(newValue, oldValue) {
                this.setDuration();
            },
            start_at(newValue, oldValue) {
                this.form.end_at = moment(newValue).add(this.duration, 'seconds').format();
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
        },

        computed: {
            start_at() {
                return this.form.start_at;
            },
            end_at() {
                return this.form.end_at;
            },
        },

        data() {
            return {
                duration: 0,
                errors: {},
                form: {
                    start_at: '',
                    end_at: '',
                    name: '',
                    item_id: 0,
                    note: '',
                    user_id: null,
                },
                id: 0,
                item: null,
                items: [],
            }
        },

        methods: {
            update() {
                var component = this;
                axios.put('/kalender/' + component.id, component.form)
                    .then( function (response) {
                        component.errors = {};
                        component.$emit('updated', response.data);
                        $('#todo-edit').modal('hide');
                    })
                    .catch( function (error) {
                        component.errors = error.response.data.errors;
                });
            },
            destroy() {
                var component = this;
                axios.delete(component.todo.path)
                    .then( function (response) {
                        component.errors = {};
                        component.$emit('deleted');
                        $('#todo-edit').modal('hide');
                    })
                    .catch( function (error) {
                        Vue.error('Termin konnte nicht gelöscht werden!');
                });
            },
            setDuration() {
                this.duration = moment.duration(moment(this.form.end_at).diff(moment(this.form.start_at))).asSeconds();
            },
        },
    };
</script>