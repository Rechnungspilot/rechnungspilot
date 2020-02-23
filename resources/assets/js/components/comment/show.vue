<template>
    <a href="#" class="list-group-item list-group-item-action">
        <div class="d-flex w-100 justify-content-between">
          <h5 class="mb-1">{{ name }}</h5>
          <small>{{ date }}</small>
        </div>
        <a :href="comment.commentable.path" v-if="'commentable' in comment">{{ comment.commentable.name }}</a>
        <p class="mb-1" v-html="comment.text"></p>
    </a>
</template>

<script>
    import moment from "moment";

    export default {

        props: [
            'comment',
        ],

        data () {
            return {

            };
        },

        computed: {
            date() {
                return moment.utc(this.comment.created_at).utcOffset(120).format('DD.MM.YYYY HH:mm');
            },
            name() {
                return this.comment.user.lastname + ', ' + this.comment.user.firstname;
            }
        },

        methods: {

        }

    };
</script>