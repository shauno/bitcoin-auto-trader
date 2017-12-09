var mainChart, otherCharts = {};

$.ajax({
    'url': 'api/v1/xbt-gap',
    'success': function(data) {
        var usd = [], zar = [], percent=[], usd_zar_rolling_gap=[], buyLine=[], sellLine=[], labels = [];

        for(item in data) {
            usd[usd.length] = data[item].xbt_usd_in_zar;
            zar[zar.length] = data[item].xbt_zar;
            percent[percent.length] = data[item].percent;
            usd_zar_rolling_gap[usd_zar_rolling_gap.length] = data[item].usd_zar_rolling_gap;
            labels[labels.length] = item;
            buyLine[buyLine.length] = data[item].usd_zar_rolling_buy_gap;
            sellLine[sellLine.length] = data[item].usd_zar_rolling_sell_gap;
        }

        mainChart = new Chart(document.getElementById("chart-xbt-gap"), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'USD converted to ZAR',
                        yAxisID: 'y-axis-rand',
                        data: usd,
                        fill: false,
                        borderColor: '#216C2A',
                        backgroundColor: '#216C2A'
                    },
                    {
                        label: 'Local ZAR',
                        yAxisID: 'y-axis-rand',
                        data: zar,
                        fill: false,
                        borderColor: '#7777FF',
                        backgroundColor: '#7777FF'
                    },
                    {
                        label: 'Gap',
                        yAxisID: 'y-axis-percent',
                        data: percent,
                        fill: false,
                        borderColor: '#dcdcdc',
                        backgroundColor: '#eeeeee'
                    },
                    {
                        label: 'Rolling Average Gap',
                        yAxisID: 'y-axis-percent',
                        data: usd_zar_rolling_gap,
                        fill: false,
                        borderColor: '#cca6bd',
                        backgroundColor: '#cca6bd'
                    },
                    {
                        label: 'Buy Marker',
                        yAxisID: 'y-axis-percent',
                        data: buyLine,
                        fill: false,
                        borderDash: [6,3],
                        borderColor: '#FFFDB2',
                        backgroundColor: '#FFFDB2'

                    },
                    {
                        label: 'Sell Marker',
                        yAxisID: 'y-axis-percent',
                        data: sellLine,
                        fill: false,
                        borderDash: [6,3],
                        borderColor: '#ffcccc',
                        backgroundColor: '#ffcccc'
                    }
                ]
            },
            options: {
                elements: {
                    point: {
                        radius: 0,
                        hitRadius: 4,
                        hoverRadius: 4
                    }
                },
                scales: {
                    yAxes: [
                        {
                            position: 'left',
                            id: 'y-axis-rand'
                        },
                        {
                            position: 'right',
                            id: 'y-axis-percent'
                        }
                    ]
                }
            }
        });

        setInterval(function() {
            updateGapChart();
        }, 60000);
    }
});

function updateGapChart() {
    var last_date = mainChart.data.labels[mainChart.data.labels.length-1];

    $.ajax({
        'url': 'api/v1/xbt-gap?from_date='+last_date,
        'success': function (data) {

            for(label in data) {
                mainChart.data.labels.push(label);

                mainChart.data.datasets[0].data.push(data[label].xbt_usd_in_zar);
                mainChart.data.datasets[1].data.push(data[label].xbt_zar);
                mainChart.data.datasets[2].data.push(data[label].percent);
                mainChart.data.datasets[3].data.push(data[label].usd_zar_rolling_gap);
                mainChart.data.datasets[4].data.push(data[label].usd_zar_rolling_buy_gap);
                mainChart.data.datasets[5].data.push(data[label].usd_zar_rolling_sell_gap);

                mainChart.data.labels.splice(0, 1);
                mainChart.data.datasets.forEach(function(dataset) {
                    dataset.data.splice(0, 1);
                });

                mainChart.update();
            }
        }
    });
}

function currencyGraph(from_iso, to_iso) {
    $.ajax({
        'url': 'api/v1/exchange-rates?from_iso='+from_iso+'&to_iso='+to_iso,
        'success': function (data) {
            var value = [], labels = [];
            for (item in data) {
                value[value.length] = data[item].rate;
                labels[labels.length] = data[item].created_at;
            }
            otherCharts[from_iso+to_iso] = new Chart(document.getElementById('chart-'+from_iso.toLowerCase()+'-'+to_iso.toLowerCase()), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: to_iso,
                            data: value,
                            fill: false,
                        }
                    ]
                },
                options: {
                    elements: {
                        point: {
                            radius: 0,
                            hitRadius: 4,
                            hoverRadius: 4
                        }
                    }
                }
            });

            setInterval(function() {
                updateCurrencyGraph(from_iso, to_iso);
            }, 120000);
        }
    });
}

function updateCurrencyGraph(from_iso, to_iso) {
    var chart = otherCharts[from_iso+to_iso];
    var last_date = chart.data.labels[chart.data.labels.length-1];

    $.ajax({
        'url': 'api/v1/exchange-rates?from_iso='+from_iso+'&to_iso='+to_iso+'&from_date='+last_date,
        'success': function (data) {
            for(var i=0; i<data.length; i++) {
                chart.data.labels.push(data[i].created_at);
                chart.data.datasets[0].data.push(data[i].rate);
                chart.data.labels.splice(0, 1);
                chart.data.datasets[0].data.splice(0, 1);
                chart.update();
            }
        }
    });
}

currencyGraph('XBT', 'JPY');
currencyGraph('XBT', 'USD');
