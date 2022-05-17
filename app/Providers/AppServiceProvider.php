<?php

namespace App\Providers;

use App\Currencies;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use App\meta;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        //
        Schema::defaultStringLength(191);
        View::composer('public.master', function ($view) {
            $meta = Meta::where('name', 'binance_general_metrics')->first();
            $meta = $meta->value;
            if($meta){
                $total_cryptos = $meta['total_cryptocurrencies'];
                $total_markets = $meta['active_market_pairs'];
                $market_cap = $meta['quote']['USD']['total_market_cap'];
                $trade_vol = $meta['quote']['USD']['total_volume_24h'];
                $btc_dominance = $meta['btc_dominance'];
                $view->with([
                    'total_cryptos' =>  $total_cryptos,
                    'total_markets' =>  $total_markets,
                    'market_cap' =>     $market_cap,
                    'trade_vol' =>      $trade_vol,
                    'btc_dominance' =>  $btc_dominance,
                ]);
            }
            $currencies = Currencies::all()->keyBy('code');
            $view->with(
                compact(
                    'currencies'
                )
            );
        });
    }
}
