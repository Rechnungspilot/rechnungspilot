<template>
    <div>
        <div class="form-group">
            <label for="date">{{ model.dateName }}</label>
            <datetime v-model="date" input-class="form-control" id="date" name="date" format="dd.MM.yyyy" @input="setDateDue"></datetime>
        </div>
        <div class="form-group">
            <label for="term_id">{{ model.term.typeName }}</label>
            <select class="form-control" id="term_id" name="term_id" v-model="termId" @change="setDateDue">
                <option v-for="(option, key) in terms" :value="option.id">{{ option.name }}</option>
            </select>
        </div>
        <div class="form-group">
            <label for="date_due">{{ model.dateDueName }}</label>
            <datetime v-model="dateDue" input-class="form-control" id="date_due" name="date_due" format="dd.MM.yyyy"></datetime>
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