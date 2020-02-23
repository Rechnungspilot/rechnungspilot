<template>
    <div class="col-md-2 d-flex align-items-center" style="height: 40px;">
        <button class="btn btn-secondary" @click="create()"><i class="fas fa-fw fa-plus-square"></i></button>
    </div>
</template>

<script>
    export default {

        props: [
            'uri',
        ],

        methods: {
            create() {
                var component = this;
                axios.post(component.uri)
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