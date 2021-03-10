<template>

    <editable :is-editing="isEditing" @editing="isEditing = $event" @updating="update()" @destroying="destroy()">

        <template v-slot:edit>
            <td class="align-middle pointer">
                <input-text v-model="form.name" placeholder="Name" :error="error('name')" @keydown.enter="update"></input-text>
            </td>
            <td class="align-middle" colspan="2">
                <tag-select :selected="item.tags" :index-path="item.tags_index_path" :path="item.tags_path" :showLabel="false"></tag-select>
            </td>
        </template>

        <template v-slot:show>
            <td class="align-middle pointer" @click="show">
                {{ item.name }}<br />
                <span class="text-muted">{{ item.original_name }}</span>
            </td>
            <td class="align-middle pointer" v-if="item.fileable == null">

            </td>
            <td class="align-middle pointer" v-else>
                <a :href="item.fileable.path">{{ item.fileable.name }}</a><br />
                <span class="text-muted">{{ item.fileable.typeName }}</span>
            </td>
            <td class="align-middle pointer">
                {{ item.tags_string }}
            </td>
        </template>

    </editable>

</template>

<script>
    import editable from '../tables/rows/editable';
    import inputText from '../form/input/text.vue';

    import { editableMixin } from "../../mixins/tables/rows/editable.js";

    export default {

        components: {
            editable,
            inputText
        },

        mixins: [
            editableMixin,
        ],

        props: {

        },

        data () {
            return {
                form: {
                    name: this.item.name,
                },
            };
        },

        methods: {
            show() {
                location.href = this.item.url;
            },
        },
    };
</script>