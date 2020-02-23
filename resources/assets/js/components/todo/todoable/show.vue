<template>
    <div class="card mb-3" v-if="model !== null">
        <div class="card-header">
            Verknüpfung
        </div>
        <div class="card-body">
            <a :href="model.project.path" v-if="type == 'App\\Projects\\Section'">{{ model.project.name }} > {{ model.name }}</a>
            <a :href="model.path" v-else>{{ model.name }}</a>
        </div>
</div>
</template>

<script>
    export default {

        props: {
            model: {
                required: true,
            },
            type: {
                type: String,
                required: false,
                default: '',
            },
        },

    };
</script>