<template>
    <div class="col-md-3">
        <div class="form-group">
            <label for="filter-completed">Erledigt</label>
            <select class="form-control" id="filter-completed" v-model="value" @change="$emit('input', value)">
                <option value="-1">Alle Aufgaben</option>
                <option value="1">Erledigt</option>
                <option value="0">Nicht Erledigt</option>
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