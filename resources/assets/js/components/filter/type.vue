<template>
    <div class="col-md-3">
        <div class="form-group">
            <label for="filter-statuses">Typ</label>
            <select class="form-control" id="filter-statuses" v-model="value" @change="$emit('input', value)">
                <option value="0">Typ wählen</option>
                <option v-for="(option, key) in options" :value="key">{{ option }}</option>
            </select>
        </div>
    </div>
</template>

<script>
    export default {
        props: [ 'options' ],

        data () {
            return {
                value: 0,
            };
        },
    };
</script>