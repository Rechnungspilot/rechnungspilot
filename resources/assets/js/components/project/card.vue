<template>
    <div class="card my-3 pointer" @click="link()">
        <div class="card-header">
            {{ item.name }}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4"><b>Unerledigt</b></div>
                <div class="col-md-8">{{ item.incompleted_todos_count }}</div>
            </div>
            <div class="row">
                <div class="col-md-4"><b>Erledigt</b></div>
                <div class="col-md-8">{{ item.completed_todos_count }}</div>
            </div>
            <p class="mt-3" style="white-space: pre;" v-html="item.description"></p>
        </div>
    </div>
</template>

<script>
    export default {

        props: [
            'item',
        ],

        methods: {
            link() {
                location.href = this.item.path;
            },
        },
    };
</script>