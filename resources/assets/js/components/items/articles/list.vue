<template>
    <div>
        <div class="d-flex align-items-center justify-content-between">
            <filter-search class="flex-grow-1" v-model="filter.searchtext" @input="searching"></filter-search>
            <button class="ml-1 btn btn-secondary btn-sm"><i class="fas fa-plus-square" @click="is_creating = !is_creating"></i></button>
        </div>
        <div class="my-3" v-show="is_creating">
            <h5>{{ model.name }} anlegen</h5>
            <div class="form-group">
                <input-text v-model="form.unit_value_formatted" :placeholder="model.unit.name + ' (' + model.unit.abbreviation + ')'" :error="error('unit_value_formatted')" @keydown.enter="create" @input="setUnitPriceFormatted()"></input-text>
            </div>
            <div class="form-group">
                <input-text v-model="form.unit_price_formatted" placeholder="Preis" :error="error('unit_price_formatted')"></input-text>
            </div>
            <div>
                <button class="btn btn-secondary btn-sm" @click="create">Anlegen</button>
            </div>
        </div>
        <ul class="list-group">
            <li class="list-group-item pointer" :class="{'active': (-1 == selected)}" :key="-1" @click="select(-1, -1)">
                <div class="d-flex align-items-center justify-content-between">
                    Vorbestellung
                </div>
                <div class="text-muted">{{ model.name }}</div>
            </li>
            <li class="list-group-item pointer" :class="{'active': (item.id == selected)}" :key="item.id" v-for="(item, index) in items" @click="select(index, item.id)">
                <div class="d-flex align-items-center justify-content-between">
                    <div>{{ item.unit_value_formatted }} {{ model.unit.abbreviation }}</div>
                    <div>{{ item.unit_price_formatted }} €</div>
                </div>
                <div class="text-muted">{{ model.name }}</div>
            </li>
        </ul>
        <div v-if="isLoading" class="p-5">
            <center>
                <span style="font-size: 48px;">
                    <i class="fas fa-spinner fa-spin"></i><br />
                </span>
                Lade Daten..
            </center>
        </div>
    </div>

</template>

<script>
    import filterSearch from '../../filter/search.vue';
    import tableBase from '../../tables/base.vue';
    import inputText from '../../form/input/text.vue';

    import { baseMixin } from '../../../mixins/tables/base.js';
    import { paginatedMixin } from '../../../mixins/tables/paginated.js';

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
            model: {
                required: true,
                type: Object,
            },
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
                    availability: 1,
                },
                form: {
                    unit_value_formatted: '',
                    unit_price_formatted: '',
                },
                is_creating: false,
                selected: this.value,
            };
        },

        methods: {
            select(index, article_id) {
                this.selected = article_id;

                if (index > -1) {
                    this.items.splice(index, 1);
                }

                this.$emit('input', this.selected);
            },
            created(item) {
                this.items.unshift(item);
                this.select(0, item.id);
                this.is_creating = false;
            },
            setUnitPriceFormatted() {
                var unit_value = Number(this.form.unit_value_formatted.replace(',', '.')),
                    unit_price = unit_value * this.model.unit_price;

                this.form.unit_price_formatted = unit_price.format(this.model.decimals, ',', '');
            },
        },

        watch: {
            indexPath() {
                this.fetch();
            },
        },
    };
</script>