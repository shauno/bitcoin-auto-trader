#Bitcoin Auto Trader

**USE AT YOUR OWN RISK**

**WARNING:** This script WILL place instantly filled buy and sell orders for your
bitcoins. It will kick your dog and sleep with your wife. Don't use it :neutral_face:

An experiment in predicting the bitcoin market in South Africa. There appears
to be a somewhat consistent arbitrage gap of ~6% between the US exchanges, and
BitX (Luno) South Africa. If that gap closes rapidly, there's a good chance the
local Bitcoin price will rise to open the gap again.

There's definitely more to it, and more markers should be added. But for now
 ¯\\\_(ツ)_/¯

Setup
---
* Clone the repo and `cd` into the directory:

* Install the PHP dependencies

    `$ composer install`

* Create the DB scheme

    `$ php artisan migrate`

* Create seed data (for now it's just the exchange rates we plan on tracking)

    `$ php artisan db:seed`
    
* Setup environment variables

    `$ cp .env.sample .env`
    
* Edit `.env` with your DB and BitX API settings

* Setup the exchange rate and auto trading URLs as cron jobs (TODO, use Lumen's
scheduler for this)

    * `POST /api/v1/exchange-rates/USD/ZAR`
    * `POST /api/v1/exchange-rates/XBT/USD`
    * `POST /api/v1/exchange-rates/XBT/ZAR`
    * `POST /api/v1/auto-trade`