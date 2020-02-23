<template>
    <div>

        <label>Priorität</label>
        <div class="d-flex mb-3">
            <div style="min-width: 34px;">
                <i class="fas fa-fw fa-minus mr-3 pointer" v-show="form.priority < 2" @click="form.priority++"></i>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="priority_2" value="2" v-model="form.priority">
                <label class="form-check-label" for="priority_2"></label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="priority_1" value="1" v-model="form.priority">
                <label class="form-check-label" for="priority_1"></label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="priority_0" value="0" v-model="form.priority">
                <label class="form-check-label" for="priority_0"></label>
            </div>
            <div style="min-width: 34px;">
                <i class="fas fa-fw fa-plus pointer" v-show="form.priority > 0" @click="form.priority--"></i>
            </div>
        </div>

        <div class="form-group">
            <label>Dienstleistung</label>
            <service-input v-model="item" :error="'item_id' in errors ? errors.item_id[0] : ''"></service-input>
        </div>

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" :class="{'is-invalid': 'name' in errors}" id="name" v-model="form.name">
            <div class="invalid-feedback" v-if="'name' in errors">
                {{ errors.name[0] }}
            </div>
        </div>

        <div class="form-group">
            <label>Beschreibung</label>
            <textarea class="form-control" v-model="form.description" rows="3"></textarea>
        </div>

        <team-input v-model="form.user_id" :options="users"></team-input>

        <div class="form-group form-check my-0 pointer">
            <input type="checkbox" class="form-check-input" id="use_dates" v-model="use_dates">
            <label class="form-check-label" for="use_dates">Zeiten eintragen</label>
        </div>

        <div class="form-row align-items-end" v-show="use_dates">
            <div class=" col-auto form-group">
                <label>Start</label>
                <calendar-date v-model="form.start_at" :error="'start_at' in errors ? errors.start_at[0] : ''"></calendar-date>
            </div>
            <div class="col-auto form-group">
                <timepicker v-model="form.start_at" :error="'start_at' in errors ? errors.start_at[0] : ''"></timepicker>
            </div>
            <div class="col"></div>
        </div>

        <div class="form-row align-items-end" v-show="use_dates">
            <div class=" col-auto form-group">
                <label>Ende</label>
                <calendar-date v-model="form.end_at" :error="'end_at' in errors ? errors.end_at[0] : ''"></calendar-date>
            </div>
            <div class="col-auto form-group">
                <timepicker v-model="form.end_at" :error="'end_at' in errors ? errors.end_at[0] : ''"></timepicker>
            </div>
            <div class="col"></div>
        </div>

        <div class="form-group">
            <label>Notiz</label>
            <textarea class="form-control" v-model="form.note" rows="3"></textarea>
        </div>

        <button class="btn btn-primary" @click="update">Speichern</button>

    </div>
</template>

<script>
    import moment from "moment";
    import Multiselect from 'vue-multiselect';

    import calendarDate from '../../components/form/input/calendar-date.vue';
    import serviceInput from '../../components/form/input/item/service.vue';
    import teamInput from '../../components/form/input/team.vue';
    import timepicker from '../../components/form/input/time.vue';

    export default {

        components: {
            calendarDate,
            Multiselect,
            serviceInput,
            teamInput,
            timepicker,
        },

        props: {
            model: {
                type: Object,
                required: true,
            },
            users: {
                type: Array,
                required: true,
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
                duration: this.model.duration,
                errors: {},
                form: {
                    description: this.model.description,
                    end_at: this.model.end_at,
                    item_id: this.model.item_id,
                    name: this.model.name,
                    note: this.model.note,
                    start_at: this.model.start_at,
                    user_id: this.model.user_id,
                    priority: this.model.priority,
                },
                item: this.model.item,
                use_dates: this.model.end_at ? true : false,
            };
        },

        methods: {
            update() {
                var component = this;
                axios.put(component.model.path, component.form)
                    .then( function (response) {
                        component.errors = {};
                        location.href = component.model.path;
                    })
                    .catch( function (error) {
                        component.errors = error.response.data.errors;
                });
            },
            setDuration() {
                this.duration = this.use_dates ? moment.duration(moment(this.form.end_at).diff(moment(this.form.start_at))).asSeconds() : 0;
            },
        },

        watch: {
            item(newValue) {
                this.form.item_id = newValue ? newValue.id : 0;
                if (newValue) {
                    if (this.form.name == '') {
                        this.form.name = newValue.name;
                    }
                    if (this.use_dates && newValue.duration > 0) {
                        this.duration = newValue.duration;
                        this.form.end_at = moment(this.start_at).add(this.duration, 'seconds').format();
                    }
                }
            },
            end_at(newValue, oldValue) {
                this.setDuration();
            },
            start_at(newValue, oldValue) {
                this.form.end_at = this.use_dates ? moment(newValue).add(this.duration, 'seconds').format() : null;
            },
            use_dates(newValue) {
                if (newValue) {
                    this.form.end_at = this.model.end_at == null ? moment(this.form.start_at).add(1, 'hour').format() : this.model.end_at;
                }
                else {
                    this.form.end_at = null;
                }
            },
        },
    };
</script>