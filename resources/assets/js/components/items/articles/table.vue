<template>

    <div>

        <div v-if="tag_item != null">
            Etikett (TODO): <i class="fas fa-tag"></i>
        </div>

        <table-base :is-loading="isLoading" :items-length="items.length" :is-showing-footer="true" :has-filter="hasFilter()" @searching="searching($event)" @creating="create">

            <template v-slot:form>
                <div class="form-group mb-0 mr-1">
                    <input-text v-model="form.unit_value_formatted" :placeholder="model.unit.name + ' (' + model.unit.abbreviation + ')'" :error="error('unit_value_formatted')" @keydown.enter="create" @input="setUnitPriceFormatted()"></input-text>
                </div>
                <div class="form-group mb-0 mr-1">
                    <input-text v-model="form.unit_price_formatted" placeholder="Preis" :error="error('unit_price_formatted')"></input-text>
                </div>
            </template>

            <template v-slot:filter>

                <filter-per-page v-model="filter.perPage" @input="fetch"></filter-per-page>

            </template>

            <template v-slot:thead>
                <tr>
                    <th width="50%">{{ model.unit.name }} ({{ model.unit.abbreviation }})</th>
                    <th class="d-none d-sm-table-cell" width="50%">Preis</th>
                    <th class="d-none d-sm-table-cell" width="100">Erstellt</th>
                    <th class="text-right" width="100">Aktion</th>
                </tr>
            </template>

            <template v-slot:tbody>
                <row :item="item" :model="model" :key="item.id" v-for="(item, index) in items" @deleted="deleted(index)" @updated="updated(index, $event)"></row>
            </template>

            <template v-slot:tfoot>
                <tr class="font-weight-bold">
                    <td class="" colspan="4">{{ items.length }} {{ model.name }}</td>
                </tr>
                <tr class="font-weight-bold">
                    <td class="">{{ Number(sums.unit_value).format(2, ',', '.') }} {{ model.unit.abbreviation }}</td>
                    <td class="d-none d-sm-table-cell">{{ Number(sums.unit_price).format(2, ',', '.') }} €</td>
                    <td class="d-none d-sm-table-cell"></td>
                    <td></td>
                </tr>
                <tr class="font-weight-bold">
                    <td class="">Ø {{ Number(sums.unit_value / items.length).format(2, ',', '.') }} {{ model.unit.abbreviation }}</td>
                    <td class="d-none d-sm-table-cell">Ø {{ Number(sums.unit_price / items.length).format(2, ',', '.') }} €</td>
                    <td class="d-none d-sm-table-cell"></td>
                    <td></td>
                </tr>
            </template>

        </table-base>

        <highcharts :options="counts_chart"></highcharts>

        <table class="table table-fixed table-hover table-striped table-sm" v-if="items.length">
            <thead>
                <tr>
                    <th>{{ model.unit.name }} ({{ model.unit.abbreviation }})</th>
                    <th>Anzahl</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(count, unit_value) in counts">
                    <td>{{ unit_value }} {{ model.unit.abbreviation }}</td>
                    <td>{{ count }}</td>
                </tr>
            </tbody>
        </table>

    </div>


</template>

<script>
    import Highcharts from 'highcharts';
    import {Chart} from 'highcharts-vue'

    import row from './row.vue';
    import filterPerPage from "../../filter/perPage.vue";
    import inputText from '../../form/input/text.vue';
    import tableBase from '../../tables/base.vue';

    import { baseMixin } from "../../../mixins/tables/base.js";
    import { paginatedMixin } from "../../../mixins/tables/paginated.js";

    export default {

        components: {
            filterPerPage,
            highcharts: Chart,
            inputText,
            row,
            tableBase,
        },

        props: {
            model: {
                required: true,
                type: Object,
            },
            createdAtDate: {
                required: false,
                type: String,
                default: null,
            }
        },

        mixins: [
            baseMixin,
            paginatedMixin,
        ],

        computed: {
            sums() {
                var sums = {
                    unit_value: 0,
                    unit_price: 0,
                };

                this.items.forEach(function (article, index) {
                    sums.unit_value += Number(article.unit_value);
                    sums.unit_price += Number(article.unit_price);
                });

                return sums;
            },
            counts() {
                var component = this,
                    counts = {};

                this.items.forEach(function (article, index) {
                    var unit_value_formatted = Number(article.unit_value).format(component.model.decimals, ',', '');

                    if (! (unit_value_formatted in counts)) {
                        counts[unit_value_formatted] = 0;
                    }

                    counts[unit_value_formatted]++;
                });

                var sorted = Object.keys(counts).sort().reduce(
                    (obj, key) => {
                        obj[key] = counts[key];
                        return obj;
                }, {});

                component.counts_chart.xAxis.categories = Object.keys(sorted);
                component.counts_chart.series[0].data = Object.values(sorted);

                return sorted;
            }
        },

        data () {

            var component = this;

            return {
                counts_chart: {
                    chart: {

                    },
                    title: {
                        text: 'Verteilung'
                    },
                    subtitle: {
                        text: ''
                    },
                    xAxis: {
                        categories: [],
                        type: 'category',
                        labels: {
                            rotation: -45,
                            style: {
                                fontSize: '13px',
                                fontFamily: 'Verdana, sans-serif'
                            }
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Anzahl'
                        },
                        tickInterval: 1,
                    },
                    legend: {
                        enabled: true
                    },
                    plotOptions: {
                        column: {
                            //stacking: 'normal'
                        }
                    },
                    series: [
                        {
                            name: component.model.unit.name,
                            type: 'column',
                            yAxis: 0,
                            data: [],
                            color: '#90ed7d',
                            index: 0,
                            tooltip: {
                                pointFormat: '<b>{point.y:.0f} Stück</b>'
                            },
                            dataLabels: {
                                enabled: true,
                                rotation: 0,
                                color: '#FFFFFF',
                                align: 'center',
                                // format: '{point.y:.2f} €', // one decimal
                                y: 0, // 10 pixels down from the top
                                style: {
                                    fontSize: '13px',
                                    fontFamily: 'Verdana, sans-serif'
                                },
                                formatter: function () {
                                    if(this.y != 0) {
                                        return (this.y ? Highcharts.numberFormat(this.y, 0) :  '');
                                    }
                                }
                            }
                        },
                    ],
                },
                filter: {
                    created_at_date: this.createdAtDate,
                    perPage: 25,
                },
                form: {
                    unit_value_formatted: '',
                    unit_price_formatted: '',
                },
                is_creating: false,
                tag_item: null,
            };
        },

        methods: {
            created(item) {
                this.items.unshift(item);
                // this.tag_item = item;
            },
            setUnitPriceFormatted() {
                var unit_value = Number(this.form.unit_value_formatted.replace(',', '.')),
                    unit_price = unit_value * this.model.unit_price;

                this.form.unit_price_formatted = unit_price.format(this.model.decimals, ',', '');
            },
        },

    };
</script>