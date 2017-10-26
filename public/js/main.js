$.ajax({
    'url': 'api/v1/xbt-gap',
    'success': function(data) {
        var usd = [], zar = [], percent=[], buyLine=[], sellLine=[], labels = [];

        for(item in data) {
            usd[usd.length] = data[item].xbt_usd_in_zar;
            zar[zar.length] = data[item].xbt_zar;
            percent[percent.length] = data[item].percent;
            labels[labels.length] = item;
            buyLine[buyLine.length] = jQuery('#chart-xbt-gap').data('buy');
            sellLine[sellLine.length] = jQuery('#chart-xbt-gap').data('sell');
        }

        new Chart(document.getElementById("chart-xbt-gap"), {
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
                        borderColor: '#cccccc',
                        backgroundColor: '#cccccc'
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
    }
});

function currencyGraph(from_iso, to_iso) {
    $.ajax({
        'url': 'api/v1/exchange-rates?from_iso='+from_iso+'&to_iso='+to_iso,
        'success': function (data) {
            var value = [], labels = [];
            for (item in data) {
                value[value.length] = data[item].rate;
                labels[labels.length] = data[item].created_at;
            }

            new Chart(document.getElementById('chart-'+from_iso.toLowerCase()+'-'+to_iso.toLowerCase()), {
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
        }
    });
}

currencyGraph('XBT', 'CNY');
currencyGraph('XBT', 'JPY');
currencyGraph('XBT', 'USD');

