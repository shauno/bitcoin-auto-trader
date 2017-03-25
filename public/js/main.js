$.ajax({
    'url': 'api/v1/xbt-gap',
    'success': function(data) {
        var usd = [], zar = [], labels = [];
        for(item in data) {
            usd[usd.length] = data[item].xbt_usd_in_zar;
            zar[zar.length] = data[item].xbt_zar;
            labels[labels.length] = item;
        }

        new Chart(document.getElementById("chart-xbt-gap"), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'USD converted to ZAR',
                        data: usd,
                        fill: false,
                        borderColor: '#216C2A',
                        backgroundColor: '#216C2A'
                    },
                    {
                        label: 'Local ZAR',
                        data: zar,
                        fill: false,
                        borderColor: '#7777FF',
                        backgroundColor: '#7777FF'
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

