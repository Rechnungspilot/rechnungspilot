<template>
    <div class="col-md-3">
        <div class="form-group">
            <label for="filter-contact">Konten</label>
            <select class="form-control" id="filter-contact" v-model="value" @change="$emit('input', value)">
                <option v-for="(option, key) in options" :value="option.id">{{ option.name }}</option>
            </select>
        </div>
    </div>
</template>

<script>
    export default {
        props: [ 'options' ],

        data () {
            return {
                value: this.options[0].id,
            };
        },
    };
</script>