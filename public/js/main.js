var usdZarChart = document.getElementById("chart-usd-zar");
var xbtUsdChart = document.getElementById("chart-xbt-usd");
var xbtZarChart = document.getElementById("chart-xbt-zar");

$.ajax({
    'url': 'api/v1/exchange-rates/USD/ZAR',
    'success': function(data) {
        var labels = [];
        var values = [];

        for (item in data) {
            labels[labels.length] = data[item].created_at;
            values[values.length] = data[item].rate;
        }
        var chart = new Chart(usdZarChart, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'USD/ZAR',
                    data: values
                }]
            }
        });
    }
});

$.ajax({
    'url': 'api/v1/exchange-rates/XBT/USD',
    'success': function(data) {
        var labels = [];
        var values = [];

        for (item in data) {
            labels[labels.length] = data[item].created_at;
            values[values.length] = data[item].rate;
        }
        var chart = new Chart(xbtUsdChart, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'XBT/USD',
                    data: values
                }]
            }
        });
    }
});

$.ajax({
    'url': 'api/v1/exchange-rates/XBT/ZAR',
    'success': function(data) {
        var labels = [];
        var values = [];

        for (item in data) {
            labels[labels.length] = data[item].created_at;
            values[values.length] = data[item].rate;
        }
        var chart = new Chart(xbtZarChart, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'XBT/ZAR',
                    data: values
                }]
            }
        });
    }
});


