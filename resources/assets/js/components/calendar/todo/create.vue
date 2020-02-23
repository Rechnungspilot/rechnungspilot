<template>
    <div class="modal fade" tabindex="-1" role="dialog" id="todo-create">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Termin anlegen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label>Dienstleistung (optional)</label>
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

                    <div class="form-group">
                        <label>Kontakt (optional)</label>
                        <contact-input v-model="contact" :value="initialContact" :error="'item_id' in errors ? errors.item_id[0] : ''"></contact-input>
                    </div>

                    <div class="form-group">
                        <label>Notiz</label>
                        <textarea class="form-control" v-model="form.note" rows="3"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" @click.prevent="create">Speichern</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from "moment";
    import Multiselect from 'vue-multiselect';
    import calendarDate from '../../form/input/calendar-date.vue';
    import timepicker from '../../form/input/time.vue';
    import serviceInput from '../../form/input/item/service.vue';
    import teamInput from '../../form/input/team.vue';
    import contactInput from '../../form/input/contact/select.vue';

    export default {

        components: {
            calendarDate,
            contactInput,
            Multiselect,
            serviceInput,
            teamInput,
            timepicker,
        },

        props: {
            initialStartAt: {
                default: '',
                type: String,
            },
            users: {
                type: Array,
                required: true,
            },
            receiptId: {
                required: false,
                default: 0,
            },
            initialContact: {
                type: Object,
                default: null,
            }
        },

        watch: {
            initialStartAt(newValue, oldValue) {
                var start_at = moment(newValue);
                this.form.start_at = start_at.format();
                this.form.end_at = moment(start_at).add(1, 'hours').format();
                this.form.item_id = 0;
                this.form.user_id = null;
                this.form.contact_id = this.initialContact ? this.initialContact.id : 0;
                this.form.note = '';
                this.form.name = '';
                this.item = null;
                this.contact = this.initialContact;
                this.items = [];
                this.contacts = [];
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
            contact(newValue) {
                this.form.contact_id = newValue ? newValue.id : 0;
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
                    contact_id: 0,
                    note: '',
                    user_id: null,
                    receipt_id: this.receiptId,
                },
                item: null,
                contact: this.initialContact,
            }
        },

        methods: {
            create() {
                var component = this;
                axios.post('/kalender', component.form)
                    .then( function (response) {
                        component.errors = {};
                        component.$emit('created', response.data);
                        $('#todo-create').modal('hide');
                    })
                    .catch( function (error) {
                        component.errors = error.response.data.errors;
                });
            },
            setDuration() {
                this.duration = moment.duration(moment(this.form.end_at).diff(moment(this.form.start_at))).asSeconds();
            },
        },
    };
</script>