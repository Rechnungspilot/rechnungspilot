<template>
        <div id="chart"></div>
</template>

<script>
    import Highcharts from 'highcharts';

    export default {

        props: [
            'model'
        ],

        data() {
            return {
                payed: [],
                outstanding: [],
                tax_value: [],
                expenses: [],
                path: (this.model == undefined ? '' : this.model.path),
            };
        },

        mounted() {
            this.fetch();
        },

        methods: {
            fetch() {
                var component = this;
                axios.get(this.path + '/umsatz')
                    .then( function (response) {
                        for(var item in response.data) {
                            component.payed.push([
                                response.data[item].key,
                                response.data[item].payed
                            ]);
                            component.outstanding.push([
                                response.data[item].key,
                                response.data[item].outstanding
                            ]);
                            component.tax_value.push([
                                response.data[item].key,
                                response.data[item].tax_value
                            ]);
                            component.expenses.push([
                                response.data[item].key,
                                response.data[item].expenses
                            ]);
                        }
                        Highcharts.chart('chart', {
                            chart: {

                            },
                            title: {
                                text: 'Umsatz der letzten 12 Monate'
                            },
                            subtitle: {
                                text: ''
                            },
                            xAxis: {
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
                                    text: 'Umsatz (€)'
                                },
                                reversedStacks: false,
                            },
                            legend: {
                                enabled: true
                            },
                            plotOptions: {
                                column: {
                                    stacking: 'normal'
                                }
                            },
                            series: [{
                                name: 'Bezahlt',
                                type: 'column',
                                yAxis: 0,
                                data: component.payed,
                                stack: 'invoice',
                                index: 0,
                                tooltip: {
                                    pointFormat: 'Bezahlt: <b>{point.y:.2f} €</b>'
                                },
                                dataLabels: {
                                    enabled: true,
                                    rotation: 0,
                                    color: '#FFFFFF',
                                    align: 'right',
                                    // format: '{point.y:.2f} €', // one decimal
                                    y: 0, // 10 pixels down from the top
                                    style: {
                                        fontSize: '13px',
                                        fontFamily: 'Verdana, sans-serif'
                                    },
                                    formatter: function () {
                                        if(this.y != 0) {
                                            return (this.y ? Highcharts.numberFormat(this.y, 2) + ' €' :  '');
                                        }
                                    }
                                }
                            },
                            {
                                name: 'Offen',
                                type: 'column',
                                data: component.outstanding,
                                stack: 'invoice',
                                index: 1,
                                yAxis: 0,
                                tooltip: {
                                    pointFormat: 'Offen: <b>{point.y:.2f} €</b>'
                                },
                                dataLabels: {
                                    enabled: true,
                                    rotation: 0,
                                    color: '#FFFFFF',
                                    align: 'right',
                                    // format: '{point.y:.2f} €', // one decimal
                                    y: 0, // 10 pixels down from the top
                                    style: {
                                        fontSize: '13px',
                                        fontFamily: 'Verdana, sans-serif'
                                    },
                                    formatter: function () {
                                        if(this.y != 0) {
                                            return (this.y ? Highcharts.numberFormat(this.y, 2) + ' €' :  '');
                                        }
                                    }
                                }
                            },
                            {
                                name: 'Ausgaben',
                                type: 'column',
                                yAxis: 0,
                                data: component.expenses,
                                stack: 'expenses',
                                tooltip: {
                                    pointFormat: 'Ausgaben: <b>{point.y:.2f} €</b>'
                                },
                                dataLabels: {
                                    enabled: true,
                                    rotation: 0,
                                    color: '#FFFFFF',
                                    align: 'right',
                                    format: '{point.y:.2f} €', // one decimal
                                    y: 0, // 10 pixels down from the top
                                    style: {
                                        fontSize: '13px',
                                        fontFamily: 'Verdana, sans-serif'
                                    }
                                }
                            }]
                        });
                    });
            }
        }

    };
</script>