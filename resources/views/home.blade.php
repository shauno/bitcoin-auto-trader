<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<?php
/**
 * @var $orders \BtcAutoTrader\Orders\Order[]
 * @var $lastOrder \BtcAutoTrader\Orders\Order
 */
?>
<div style="width: 1200px; display: inline-block;">
    <canvas id="chart-xbt-gap"></canvas>
</div>

<div style="width: 150px; display: inline-block; vertical-align: top">
    <form method="post" action="{{ route($lastOrder->getType() === 'SELL' ? 'instant-buy-order' : 'instant-sell-order') }}">
        <input type="password" name="password">
        <button type="submit" style="width:100%;"><?php $lastOrder->getType() === 'SELL' ? 'But' : 'Sell' ?></button>
    </form>
</div>

<br />

<div>
    <div style="width: 400px; display: inline-block;">
        <canvas id="chart-xbt-usd"></canvas>
    </div>

    <div style="width: 400px; display: inline-block;">
        <canvas id="chart-xbt-cny"></canvas>
    </div>

    <div style="width: 400px; display: inline-block;">
        <canvas id="chart-xbt-jpy"></canvas>
    </div>
</div>

<div>
    <div style="width: 600px; display: inline-block; vertical-align: top;">
        <table border="1">
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Rand</th>
                <th>BTC</th>
                <th>Rate</th>
                <th>Fee</th>
            </tr>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->created_at }}</td>
                    <td>{{ $order->type }}</td>
                    <td>{{ round($order->counter, 2) }}</td>
                    <td>{{ $order->base }}</td>
                    <td>{{ round($order->rate, 2) }}</td>
                    <td>{{ $order->fee_btc }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
<script src="/js/Chart.min.js"></script>
<script src="/js/jquery-3.2.0.min.js"></script>
<script src="/js/main.js"></script>

</body>
</html>