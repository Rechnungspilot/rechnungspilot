<template>
    <div class="form-group" v-show="Object.keys(options).length > 1">
        <label for="team_id">Team</label>
        <select class="form-control" id="team_id" :value="value" @change="$emit('input', $event.target.value)">
            <option :value="null" v-if="(forceTeam == false)">Nicht zugeordnet</option>
            <option v-for="(option, key) in options" :value="option.id">{{ option.name }}</option>
        </select>
    </div>
</template>

<script>
    export default {
        props: {
            options: {
                type: Array,
                required: true,
            },
            value: {
                required: true,
            },
            forceTeam: {
                required: false,
                default: false,
            },
        },
    };
</script>