var gapChart = document.getElementById("chart-xbt-gap");

$.ajax({
    'url': 'api/v1/xbt-gap',
    'success': function(data) {
        var usd = [], zar = [], labels = [];
        for(item in data) {
            usd[usd.length] = data[item].xbt_usd_in_zar;
            zar[zar.length] = data[item].xbt_zar;
            labels[labels.length] = item;
        }

        new Chart(gapChart, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'USD converted to ZAR',
                        data: usd,
                        fill: false
                    },
                    {
                        label: 'Local ZAR',
                        data: zar,
                        fill: false
                    }

                ]
            },
            lineTension: 0
        });
    }
});

