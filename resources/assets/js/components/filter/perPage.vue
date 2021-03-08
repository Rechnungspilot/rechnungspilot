<template>
    <div class="col-md-3">
        <div class="form-group">
            <label for="filter-per-page">Einträge</label>
            <select class="form-control form-control-sm" id="filter-per-page" v-model="value" @change="$emit('input', value)">
                <option value="1">1 Einträge</option>
                <option value="25">25 Einträge</option>
                <option value="50">50 Einträge</option>
                <option value="100">100 Einträget</option>
            </select>
        </div>
    </div>
</template>

<script>
    export default {

        data () {
            return {
                value: 25,
            };
        },
    };
</script>