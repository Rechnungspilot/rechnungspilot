<template>
    <div>
        <datetime v-model="date" value-zone="local" type="datetime" :input-class="{'form-control': true, 'is-invalid': error != ''}" id="date" :name="name" format="dd.MM.yyyy HH:mm" @input="$emit('input', date)"></datetime>
        <div class="invalid-feedback" v-text="error ? error : ''" :style="{ display: error ? 'block' : 'none;' }"></div>
    </div>
</template>

<script>
    import moment from "moment";

    export default {

        props: [
            'error',
            'name',
            'value',
        ],

        watch: {
            value (newValue, oldValue) {
                this.date = moment(newValue).format();
            },
        },

        data() {
            return {
                date: moment(this.value).format(),
            };
        },
    };
</script>