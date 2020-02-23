<template>
    <div class="mt-3">
        <button class="btn btn-secondary btn-block" @click="create()"><i class="fas fa-fw fa-plus-square"></i></button>
    </div>
</template>

<script>
    export default {

        props: [
            'uri',
            'projecSectionId',
        ],

        methods: {
            create() {
                var component = this;
                axios.post(component.uri, {
                        'project_section_id': component.projectSectionId,
                    })
                    .then(function (response) {
                        component.$emit('created', response.data);
                    })
                    .catch(function (error) {
                        console.log(error);
                });
            },
        },
    };
</script>