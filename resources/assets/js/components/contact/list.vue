<template>
    <div>
        <div class="d-flex align-items-center justify-content-between">
            <filter-search class="flex-grow-1" v-model="filter.searchtext" @input="searching"></filter-search>
            <button class="ml-1 btn btn-secondary btn-sm"><i class="fas fa-plus-square" @click="is_creating = !is_creating"></i></button>
        </div>
        <div class="my-3" v-show="is_creating">
            <h5>Kontakt anlegen</h5>
            <div class="form-group">
                <input-text v-model="form.firstname" placeholder="Vorname" :error="error('firstname')" @keydown.enter="create"></input-text>
            </div>
            <div class="form-group">
                <input-text v-model="form.lastname" placeholder="Nachname" :error="error('lastname')" @keydown.enter="create"></input-text>
            </div>
            <div class="form-group">
                <input-text v-model="form.email" placeholder="E-Mail" :error="error('email')" @keydown.enter="create"></input-text>
            </div>
            <div class="form-group">
                <input-text v-model="form.mobilenumber" placeholder="Mobil" :error="error('mobilenumber')" @keydown.enter="create"></input-text>
            </div>
            <div>
                <button class="btn btn-secondary btn-sm" @click="create">Anlegen</button>
            </div>
        </div>
        <ul class="list-group">
            <li class="list-group-item pointer" :class="{'active': (item.id == selected)}" :key="item.id" v-for="(item, index) in items" @click="select(item.id)">
                {{ item.name }}
            </li>
        </ul>
    </div>

</template>

<script>
    import filterSearch from '../filter/search.vue';
    import tableBase from '../tables/base.vue';
    import inputText from '../form/input/text.vue';

    import { baseMixin } from '../../mixins/tables/base.js';
    import { paginatedMixin } from '../../mixins/tables/paginated.js';

    export default {

        components: {
            filterSearch,
            inputText,
            tableBase,
        },

        mixins: [
            baseMixin,
            paginatedMixin,
        ],

        props: {
            value: {
                required: false,
                type: Number,
                default: 0,
            },
        },

        data () {
            return {
                filter: {
                    tags: [],
                    perPage: 25,
                },
                form: {
                    firstname: '',
                    lastname: '',
                    email: '',
                    phonenumber: '',
                },
                is_creating: false,
                selected: this.value,
            };
        },

        methods: {
            select(contact_id) {
                if (this.selected == contact_id) {
                    this.selected = 0;
                }
                else {
                    this.selected = contact_id;
                }

                this.$emit('input', this.selected);
            },
            created(item) {
                this.items.unshift(item);
                this.select(item.id);
                this.is_creating = false;
            },
        },
    };
</script>