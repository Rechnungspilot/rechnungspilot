<template>
    <div>
        <div class="form-group">
            <label for="send_mail">Sendeoptionen</label>
            <select class="form-control" :class="'send_mail' in errors ? 'is-invalid' : ''" id="send_mail" name="send_mail" v-model="sendMail">
                <option v-for="(option, index) in sendMailOptions" :value="index">{{ option }}</option>
            </select>
            <div class="invalid-feedback" v-text="'send_mail' in errors ? errors.send_mail[0] : ''"></div>
        </div>

        <div class="form-group" v-show="sendMail == 1">
            <label for="email">E-Mail</label>
            <input class="form-control" :class="'name' in errors ? 'is-invalid' : ''" type="text" id="email" name="email" v-model="email"></input>
            <div class="invalid-feedback" v-text="'email' in errors ? errors.email[0] : ''"></div>
        </div>

        <div class="form-group">
            <label for="start_at">Erste Ausführung</label>
            <date-input id="start_at" name="start_at" v-model="startAt" :error="(('start_at' in errors) ? errors.start_at[0] : '')"></date-input>
        </div>

        <div class="form-group">
            <label for="next_at">Nächste Ausführung</label>
            <date-input id="next_at" name="next_at" v-model="nextAt" :error="(('next_at' in errors) ? errors.next_at[0] : '')" @input="setLastAt"></date-input>
        </div>

        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="interval_value">Interval</label>
                <input class="form-control" :class="'name' in errors ? 'is-invalid' : ''" type="text" id="interval_value" name="interval_value" v-model="intervalValue" @input="setLastAt">
                <div class="invalid-feedback" v-text="'interval_value' in errors ? errors.interval_value[0] : ''"></div>
            </div>
            <div class="form-group col-md-8">
                <label for="interval_unit">Einheit</label>
                <select class="form-control" name="interval_unit" id="interval_unit" v-model="intervalUnit" @change="setLastAt">
                    <option v-for="(option, index) in intervalUnits" :value="index">{{ option }}</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="last_type">Dauer</label>
            <select class="form-control" :class="'last_type' in errors ? 'is-invalid' : ''" id="last_type" name="last_type" v-model="lastType" @change="setLastAt">
                <option value="0">Unbegrenz</option>
                <option value="1">Anzahl angeben</option>
                <option value="2">Enddatum angeben</option>
            </select>
            <div class="invalid-feedback" v-text="'last_type' in errors ? errors.last_type[0] : ''"></div>
        </div>

        <div class="form-group" v-show="lastType == 1">
            <label for="last_count">Verbleibende Ausführungen</label>
            <input class="form-control" :class="'name' in errors ? 'is-invalid' : ''" type="text" id="last_count" name="last_count" v-model="lastCount" @input="setLastAt" number>
            <div class="invalid-feedback" v-text="'last_count' in errors ? errors.last_count[0] : ''"></div>
        </div>

        <div class="form-group" v-show="lastType == 2">
            <label for="last_at">Letzte Ausführung</label>
            <date-input id="last_at" name="last_at" v-model="lastAt" :error="(('last_at' in errors) ? errors.last_at[0] : '')"></date-input>
        </div>

    </div>
</template>

<script>
    import moment from "moment";

    export default {

        props: [
            'intervalUnits',
            'model',
            'sendMailOptions',
        ],

        data () {
            return {
                sendMail: this.model.send_mail,
                email: this.model.email,
                startAt: moment(this.model.start_at).format(),
                nextAt: moment(this.model.next_at).format(),
                lastType: this.model.last_type,
                lastAt: moment(this.model.last_at).format(),
                lastCount: this.model.last_count,
                intervalValue: this.model.interval_value,
                intervalUnit: this.model.interval_unit,
                errors: {},
            };
        },

        methods: {
            setLastAt() {
                if (this.lastType == 0)
                {
                    this.lastCount = 0;
                    this.lastAt = null;
                    return;
                }
                this.lastAt = moment(this.nextAt).add((this.lastType == 1 ? parseInt(this.lastCount) : 1) * this.intervalValue, this.intervalUnit).format();
            }
        },
    };
</script>