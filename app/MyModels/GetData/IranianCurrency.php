<?php
/**
 * Created by PhpStorm.
 * User: Artin
 * Date: 6/20/2020
 * Time: 2:14 PM
 */

namespace App\MyModels\GetData;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Currencies;

class IranianCurrency
{
    protected $LOG_CHANNEL = "coin_data";

    public function get_currency()
    {
        $response = Http::get("https://api.tgju.online/v1/data/sana/json");
        if ($response->successful()) {
            $data = $response->json();
            Currencies::updateOrCreate([
                    'code' => 'IRT'
                ]
                ,
                [
                    'symbol' => 'T',
                    'usd_buy_price' => $data['sana']['data'][0]['p']/10,
                    'name' => 'تومان'

                ]
            );

        } else Log::channel($this->LOG_CHANNEL)->info("TJGU unsuccessful : " . $response->body());
    }
}
