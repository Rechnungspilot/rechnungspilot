<template>
    <div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label col-form-label-sm" for="date">{{ model.dateName }}</label>
            <div class="col-sm-8">
                <datetime v-model="date" input-class="form-control form-control-sm" id="date" name="date" format="dd.MM.yyyy" @input="setDateDue"></datetime>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label col-form-label-sm" for="term_id">{{ model.term.typeName }}</label>
            <div class="col-sm-8">
                <select class="form-control form-control-sm" id="term_id" name="term_id" v-model="termId" @change="setDateDue">
                    <option v-for="(option, key) in terms" :value="option.id">{{ option.name }}</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label col-form-label-sm" for="date_due">{{ model.dateDueName }}</label>
            <div class="col-sm-8">
                <datetime v-model="dateDue" input-class="form-control form-control-sm" id="date_due" name="date_due" format="dd.MM.yyyy"></datetime>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from "moment";

    export default {

        props: [
            'model',
            'date_prop',
            'date_due',
            'term_id',
            'terms',
        ],

        data () {

            var arr = {
                termId: this.model.term_id,
                date: moment.parseZone(this.model.date).format(),
                dateDue: moment.parseZone(this.model.date_due).format(),
            };

            return arr;
        },

        methods: {
            setDateDue() {
                this.dateDue = moment(this.date).add(this.terms[this.termId].days, 'days').format();
            }
        },
    };
</script>