<template>
    <div class="col-md-3">
        <div class="form-group">
            <label for="filter-itemtype">Artikeltyp</label>
            <select class="form-control" id="filter-itemtype" v-model="value" @change="$emit('input', value)">
                <option value="-1">Artikeltyp wählen</option>
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
                value: -1,
            };
        },
    };
</script>