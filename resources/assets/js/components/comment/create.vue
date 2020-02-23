<template>
    <div>
        <div class="form-group">
            <label for="text">Kommentar</label>
            <textarea class="form-control" id="text" rows="3" v-model="text"></textarea>
        </div>

        <button class="btn btn-primary" @click="create">Kommentieren</button>
    </div>
</template>

<script>
    export default {

        props: [
            'id',
            'uri',
        ],

        data () {
            return {
                text: '',
            };
        },

        methods: {
            create() {
                var component = this;
                axios.post(this.uri + '/' + component.id + '/kommentare', {
                    'text': component.text,
                })
                    .then(function (response) {
                        component.$emit('comment-created', {
                            comment: response.data
                        });
                        component.text = '';
                    });
            },
        }

    };
</script>