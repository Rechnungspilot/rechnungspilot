<template>
    <div class="col-md-3">
        <div class="form-group">
            <label class="col-form-label col-form-label-sm" for="filter-year">Jahr</label>
            <select class="form-control form-control-sm" id="filter-year" v-model="value" @change="$emit('input', value)">
                <option value="null">Jahr wählen</option>
                <option v-for="(year, key) in years" :value="year">{{ year }}</option>
            </select>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            const current_year = (new Date()).getFullYear();
            let years = [];
            for (let year = 2020; year <= (current_year + 1); year++) {
                years.push(year);
            }

            return {
                value: current_year,
                years: years,
            };
        },
    };
</script>