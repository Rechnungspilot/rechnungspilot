<template>
    <div class="col-md-3">
        <div class="form-group">
            <label class="col-form-label col-form-label-sm" for="filter-statuses">Status</label>
            <select class="form-control form-control-sm" id="filter-statuses" v-model="value" @change="$emit('input', value)">
                <option value="0">Status wählen</option>
                <option v-for="(option, key) in options" :value="key">{{ option }}</option>
            </select>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'initialValue',
            'options',
        ],

        data () {
            return {
                value: this.initialValue || 0,
            };
        },
    };
</script>