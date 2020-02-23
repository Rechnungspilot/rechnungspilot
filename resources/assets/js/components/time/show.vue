<template>
    <div>
        <create :user-id="userId" v-if="time == null" @created="created($event)"></create>
        <div class="card" v-else>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4"><b>Gestartet</b></div>
                    <div class="col-md-8">{{ dateFormat(time.start_at) }}</div>
                </div>
                <div class="row">
                    <div class="col-md-4"><b>Dauer</b></div>
                    <div class="col-md-8">{{ duration }} h</div>
                </div>
                <div class="row">
                    <div class="col-md-4">&nbsp;</div>
                    <div class="col-md-8"></div>
                </div>
                <div class="row">
                    <div class="col-md-4"><b>Dienstleistung</b></div>
                    <div class="col-md-8">{{ time.item.name }}</div>
                </div>
                <template v-if="time.timeable !== null">
                    <div class="row">
                        <div class="col-md-4"><b>Aufgabe</b></div>
                        <div class="col-md-8">{{ time.timeable.name }}</div>
                    </div>
                    <div class="row" v-if="time.timeable.todoable_type == 'App\\Receipts\\Receipt'">
                        <div class="col-md-4"><label for="complete-order" class="form-checkbox"><b>Auftrag {{ time.timeable.todoable.name }} abschließen?</b></label></div>
                        <div class="col-md-8"><input v-model="form.completeOrder" id="complete-order" type="checkbox"></div>
                    </div>
                </template>
                <div class="form-group">
                    <label><b>Notiz</b></label>
                    <textarea class="form-control" v-model="form.note"></textarea>
                </div>
                <small><b>Hinweis: </b>Diese Seite kann geschlossen werden. Die Zeit läuft weiter, bis sie beendet wird.</small>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-primary" @click="stop()">Beenden</button>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from "moment";
    import Multiselect from 'vue-multiselect';
    import create from './create.vue';

    export default {

        components: {
            create,
            Multiselect,
        },

        props: [
            'userId',
            'runningTime',
        ],

        data() {
            return {
                time: this.runningTime,
                uri: '/zeiterfassung',
                duration: '00:00',
                durationInterval: null,
                form: {
                    note: this.runningTime ? this.runningTime.note : '',
                    completeOrder: false,
                }
            };
        },

        mounted() {
            if (this.runningTime === null) {
                return;
            }

            this.setDuration();
            this.setDurationInterval();
        },

        methods: {
            created(time) {
                this.time = time;
                this.setDurationInterval();
                Vue.success('Zeiterfassung gestartet');
            },
            stop() {
                var component = this;
                axios.delete(component.uri + '/' + component.time.id, {
                    data: component.form
                })
                    .then(function (response) {
                        component.time = null;
                        clearInterval(component.durationInterval);
                        Vue.success('Zeiterfassung beendet');
                    })
                    .catch(function (error) {
                        console.log(error);
                });
            },
            setDurationInterval() {
                this.durationInterval = setInterval(function () {
                    this.setDuration();
                }.bind(this), 60000);
            },
            setDuration() {
                var diff = moment.duration(moment().diff(moment(this.time.start_at)));
                var hours = Math.floor(diff.asHours());
                var mins  = Math.floor(diff.asMinutes()) - hours * 60;
                var pad = '00';

                this.duration = (pad + hours).slice(-2) + ':' + (pad + mins).slice(-2);
            },
            dateFormat(date) {
                return moment(date).format('DD.MM.YYYY HH:mm')
            },
        },

    };
</script>