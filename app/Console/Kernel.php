<?php

namespace App\Console;

use App\MyModels\GetData\Binance;
use App\MyModels\GetData\CoinGecko;
use App\MyModels\GetData\CoinMap;
use App\MyModels\GetData\CryptoCompare;
use App\MyModels\GetData\ForeignCurrencies;
use App\MyModels\GetData\IranianCurrency;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $coingecko = new CoinGecko();
        $cryptocompare = new CryptoCompare();


        $schedule->call(function () use ($coingecko) {

            $coingecko->get_coins();

        })->everyMinute()->runInBackground();

        $schedule->call(function () {

            $iranian_currency = new IranianCurrency();
            $iranian_currency->get_currency();
            $foreign_currency = new ForeignCurrencies();
            $foreign_currency->get_all_currencies();

        })->hourlyAt(15)->runInBackground();

        $schedule->call(function () use($cryptocompare) {

            $binance = new Binance();
            $binance->get_global_metrics();
            $cryptocompare->save_trading_signals();
            $coinmap = new CoinMap();
            $coinmap->get_venues_periodically();

        })->dailyAt('1:00')->runInBackground();

        $schedule->call(function () use ($coingecko,$cryptocompare) {

            $coingecko->get_exchange_ids();
            $coingecko->sync_additional_coin_data();
            $coingecko->sync_exchanges_data();
            $cryptocompare->save_mining_pools();
            $cryptocompare->save_first_200_wallet();
            $cryptocompare->save_mining_equipments();
            $cryptocompare->save_mining_companies();


        })->monthlyOn(2, '2:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
