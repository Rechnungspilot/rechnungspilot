<template>
    <div>
        <datetime :auto="true" v-model="date" type="datetime" value-zone="local" input-class="form-control" id="date" :name="name" format="dd.MM.yyyy" @input="$emit('input', date)"></datetime>
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
            value() {
                this.date = moment(this.value).format();
            }
        },

        data() {
            return {
                date: moment(this.value).format(),
            };
        },
    };
</script>