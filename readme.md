#Bitcoin Auto Trader

**USE AT YOUR OWN RISK**

**WARNING:** This script WILL place instantly filled buy and sell orders for your
bitcoins. It will kick your dog and sleep with your wife. Don't use it :neutral_face:

An experiment in predicting the bitcoin market in South Africa. ~There appears
to be a somewhat consistent arbitrage gap of 6% between the US exchanges, and
BitX (Luno) South Africa.~ There **was** a pretty consistent 6% arbitrage gap in the
early days. That gap can now be between 1%-25%. But the if the current gap closes
rapidly, there's a good chance the local Bitcoin price will rise to open the gap again.

This script now monitors the gap and tracks a 2 hour rolling average. The buy and sell
markers are calculated off the rolling average requiring much less monitoring and config
tweaking depending on what dumb thing some world leader says.

There's definitely more to it than this, and more markers should be added. But for now
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
    
* Edit `.env` with your DB and ~~BitX~~ Luno API settings

* Setup the bulk exchange rate tracking URL as a cron job

    * `POST /api/v1/exchange-rates/bulk-update`
    
* Setup the auto trade URL as a cron job

    * `POST /api/v1/auto-trade`
