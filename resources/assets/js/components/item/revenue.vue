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
                revenue: [],
                price: [],
                quantity: [],
            };
        },

        mounted() {
            this.fetch();
        },

        methods: {
            fetch() {
                var component = this;
                axios.get(this.model.path + '/umsatz')
                    .then( function (response) {
                        component.revenue = [];
                        for(var item in response.data) {
                            component.revenue.push([
                                response.data[item].key,
                                response.data[item].revenue
                            ]);
                            component.price.push([
                                response.data[item].key,
                                response.data[item].price
                            ]);
                            component.quantity.push([
                                response.data[item].key,
                                response.data[item].quantity
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
                            yAxis: [{
                                min: 0,
                                title: {
                                    text: 'Umsatz (€)'
                                },
                            },
                            {
                                min: 0,
                                title: {
                                    text: 'Durchschnittspreis (€)'
                                },
                                opposite: true
                            },
                            {
                                min: 0,
                                title: {
                                    text: 'Menge (€)'
                                },
                                opposite: true
                            }],
                            legend: {
                                enabled: true
                            },
                            series: [{
                                name: 'Umsatz',
                                type: 'column',
                                yAxis: 0,
                                data: component.revenue,
                                tooltip: {
                                    pointFormat: 'Umsatz: <b>{point.y:.2f} €</b>'
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
                            },
                            {
                                name: 'Durchschnittspreis',
                                type: 'line',
                                data: component.price,
                                yAxis: 1,
                                tooltip: {
                                    pointFormat: 'Durchschnittspreis: <b>{point.y:.2f} €</b>'
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
                            },
                            {
                                name: 'Menge',
                                type: 'line',
                                data: component.quantity,
                                yAxis: 2,
                                tooltip: {
                                    pointFormat: 'Menge: <b>{point.y:.2f} €</b>'
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