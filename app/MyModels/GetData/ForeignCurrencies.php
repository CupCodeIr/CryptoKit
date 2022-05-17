<?php
/**
 * Created by PhpStorm.
 * User: Artin
 * Date: 6/20/2020
 * Time: 8:49 PM
 */

namespace App\MyModels\GetData;

use Illuminate\Support\Facades\Http;
use App\Currencies;

class ForeignCurrencies
{

    public function get_all_currencies()
    {
        $response = Http::get("https://currencyapi.net/api/v1/rates",
            [
                'key' => 'H078gBb5Qa1cnNslxs9heysXsJDfM1DnkNLB',
                'limit' => 'USD,CAD,TRY,EUR'
            ]);
        if ($response->successful()) {
            $data = $response->json();

            Currencies::updateOrCreate([
                    'code' => 'TRY'
                ]
                ,
                [
                    'name' => 'لیر ترکیه',
                    'symbol' => '₺',
                    'usd_buy_price' => $data['rates']['TRY'],
                ]
            );
            Currencies::updateOrCreate([
                    'code' => 'USD'
                ]
                ,
                [
                    'symbol' => '$',
                    'usd_buy_price' => 1,
                    'name' => 'دلار آمریکا'

                ]
            );
            Currencies::updateOrCreate([
                    'code' => 'EUR'
                ]
                ,
                [
                    'symbol' => '€',
                    'usd_buy_price' => $data['rates']['EUR'],
                    'name' => 'یورو'

                ]
            );
            Currencies::updateOrCreate([
                    'code' => 'CAD'
                ]
                ,
                [
                    'symbol' => 'C$',
                    'usd_buy_price' => $data['rates']['CAD'],
                    'name' => 'دلار کانادا'

                ]
            );

        } else Log::channel($this->LOG_CHANNEL)->info("CurrencyAPI unsuccessful : " . $response->body());
    }
}