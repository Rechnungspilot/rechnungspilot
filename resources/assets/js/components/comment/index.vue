<template>
    <div class="mb-5">
        <comment-create :id="item.id" :uri="uri" @comment-created="add($event)" v-if="item != undefined"></comment-create>
        <br />
        <div v-if="isLoading" class="p-5">
            <center>
                <span style="font-size: 48px;">
                    <i class="fas fa-spinner fa-spin"></i><br />
                </span>
                Lade Daten..
            </center>
        </div>
        <div class="list-group" v-else-if="comments.length">
            <comment-show v-for="(comment, index) in comments" :key="comment.id" :comment="comment"></comment-show>
        </div>
        <div class="alert alert-dark" v-else>
            <center>
                Keine Kommentare vorhanden
            </center>
        </div>
        <nav aria-label="Page navigation example">
            <ul class="pagination" v-show="paginate.lastPage > 1">
                <li class="page-item" v-show="paginate.prevPageUrl">
                    <a class="page-link" href="#" @click.prevent="page--">Previous</a>
                </li>

                <li class="page-item" v-for="n in paginate.lastPage" v-bind:class="{ active: (n == page) }"><a class="page-link" href="#" @click.prevent="page = n">{{ n }}</a></li>

                <li class="page-item" v-show="paginate.nextPageUrl">
                    <a class="page-link" href="#" @click.prevent="page++">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
    import commentCreate from "./create.vue";
    import commentShow from "./show.vue";

    export default {

        components: {
            commentCreate,
            commentShow,
        },

        props: [
            'item',
            'uri',
        ],

        data () {
            return {
                isLoading: true,
                comments: [],
                page: 1,
                paginate: {
                    nextPageUrl: null,
                    prevPageUrl: null,
                    lastPage: 0,
                },
                path: this.item == undefined ? '' : this.uri + '/' + this.item.id,
            };
        },

        mounted() {
            this.fetch();
        },

        watch: {
            page () {
                this.fetch();
            },
        },

        methods: {
            add(event) {
                this.comments.unshift(event.comment);
                // this.comments.splice(-1,1);
            },
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get(component.path + '/kommentare?page=' + component.page)
                    .then(function (response) {
                        component.comments = response.data.data;
                        component.page = response.data.current_page;
                        component.paginate.nextPageUrl = response.data.next_page_url;
                        component.paginate.prevPageUrl = response.data.prev_page_url;
                        component.paginate.lastPage = response.data.last_page;
                        component.isLoading = false;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
        },

    };
</script>