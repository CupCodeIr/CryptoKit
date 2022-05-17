<?php
/**
 * Created by PhpStorm.
 * User: Artin
 * Date: 6/18/2020
 * Time: 9:38 PM
 */

namespace App\MyModels\GetData;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Coin;
use App\meta;

class Binance
{
    protected $API_KEY = "63947579-4000-4390-92aa-87a778d96552";
    protected $LOG_CHANNEL = "coin_data";


    public function get_all_coins()
    {
        $response = Http::get(
            "https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest",
            [
                'CMC_PRO_API_KEY' => $this->API_KEY
            ]
        );
        if ($response->successful()) {
            $data = $response->json();
            if ($data['status']['error_code'] == 0) {
                foreach ($data['data'] as $datum) {
                    Coin::updateOrCreate([
                            'cmc_id' => $datum['id']
                        ]
                        ,
                        [
                            'name' => $datum['name'],
                            'symbol' => $datum['symbol'],
                            'slug' => $datum['slug'],
                            'cmc_rank' => $datum['cmc_rank'],
                            'market_cap' => $datum['quote']['USD']['market_cap'],
                            'price' => $datum['quote']['USD']['price'],
                            'vol_24' => $datum['quote']['USD']['volume_24h'],
                            'circulating' => $datum['circulating_supply'],
                            'total_supply' => $datum['total_supply'],
                            'change_24' => $datum['quote']['USD']['percent_change_24h'],
                            'source' => 'binance',
                            'tags' => json_encode($datum['tags'])
                        ]
                    );
                }

            } else {
                Log::channel($this->LOG_CHANNEL)->info("Binance > Code : " . $data->status->error_code . " | Message : " . $data->status->error_message);
            }
        } else Log::channel($this->LOG_CHANNEL)->info("Binance unsuccessful : " . $response->body());
    }

    public function get_global_metrics()
    {
        $response = Http::get(
            "https://pro-api.coinmarketcap.com/v1/global-metrics/quotes/latest",
            [
                'CMC_PRO_API_KEY' => $this->API_KEY
            ]
        );
        if ($response->successful()) {
            $data = $response->json();
            if ($data['status']['error_code'] == 0) {
                    Meta::updateOrCreate([
                            'name' => 'binance_general_metrics'
                        ]
                        ,
                        [
                            'value' => $data['data'],
                            'group' => 'data',
                        ]
                    );


            } else {
                Log::channel($this->LOG_CHANNEL)->info("Binance > Code : " . $data['status']['error_code'] . " | Message : " . $data['status']['error_message']);
            }
        } else Log::channel($this->LOG_CHANNEL)->info("Binance unsuccessful : " . $response->body());
    }
}
