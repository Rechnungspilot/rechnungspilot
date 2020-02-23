<template>
    <div class="col-md-3" v-show="options.length > 1">
        <div class="form-group">
            <label for="filter-team">Team</label>
            <select class="form-control" id="filter-team" v-model="value" @change="$emit('input', value)">
                <option value="0">Teammitglied wählen</option>
                <option v-for="(option, key) in options" :value="option.id">{{ option.name }}</option>
            </select>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            options: {
                required: true,
            },
        },

        data () {
            return {
                value: 0,
            };
        },
    };
</script>