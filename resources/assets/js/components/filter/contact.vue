<template>
    <div class="col-md-3">
        <div class="form-group">
            <label class="col-form-label col-form-label-sm" for="filter-contact">Kontakt</label>
            <select class="form-control form-control-sm" id="filter-contact" v-model="value" @change="$emit('input', value)">
                <option value="0">Kontakt wählen</option>
                <option v-for="(option, key) in options" :value="option.id">{{ option.name }}</option>
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