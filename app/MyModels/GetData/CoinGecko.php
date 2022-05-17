<?php
/**
 * Created by PhpStorm.
 * User: Artin
 * Date: 6/18/2020
 * Time: 9:38 PM
 */

namespace App\MyModels\GetData;

use App\Country;
use App\Exchange;
use App\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Coin;
use Illuminate\Support\Facades\Storage;

class CoinGecko
{
    protected $LOG_CHANNEL = "coin_data";
    protected $COINGECKO_API_URL = "https://api.coingecko.com/api/v3";

    public function get_coins()
    {
        $data = $this->get_data('/coins?vs_currency=usd&order=market_cap_desc&per_page=200&page=1&sparkline=true&localization=false&tickers=false&developer_data=false', 'get_coins');
        if ($data !== false) {

            $records = [];

            foreach ($data as $datum) {

                $records [] = [
                    'slug' => $datum['id'] . "-" . $datum['symbol'],
                    'name' => $datum['name'],
                    'source_id' => $datum['id'],
                    'symbol' => $datum['symbol'],
                    'name_persian' => $datum['name'],
                    'price' => $datum['market_data']['current_price']['usd'],
                    'market_cap' => $datum['market_data']['market_cap']['usd'],
                    'vol_24' => $datum['market_data']['total_volume']['usd'],
                    'high_24' => $datum['market_data']['high_24h']['usd'],
                    'low_24' => $datum['market_data']['low_24h']['usd'],
                    'price_change_24' => round($datum['market_data']['price_change_percentage_24h'], 3),
                    'market_cap_change_percentage_24h' => round($datum['market_data']['market_cap_change_percentage_24h'], 3),
                    'total_supply' => $datum['market_data']['total_supply'],
                    'circulating' => round(floatval($datum['market_data']['circulating_supply']), 3),
                    '7d_sparkline' => json_encode($datum['market_data']['sparkline_7d']['price']),
                    'block_time' => (float)$datum['block_time_in_minutes'],
                    'source' => 'coingecko',
                    'created_at' =>  date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ];


            }
            DB::table('coins')->upsert($records,['source_id'],[
                'name',
                'symbol',
                'price',
                'market_cap',
                'vol_24',
                'high_24',
                'low_24',
                'price_change_24',
                'market_cap_change_percentage_24h',
                'circulating',
                '7d_sparkline',
                'block_time',
                'updated_at',
            ]);


        }


    }

    public function get_additional_coin_data($coin_id)
    {

        $coin = Coin::active()->where([
            'source_id' => $coin_id

        ])->first();

        if ($coin) {

            $data = $this->get_data("/coins/$coin_id?localization=false&tickers=false&developer_data=false&community_data=false", 'get_additional_coin_data');
            if ($data !== false)
                if (!isset($data['error'])) {

                    $image_path = $this->download_icon('coin', $data['image']['large'], $coin_id . "-" . $data['symbol']);
                    $coin_market_data = $data['market_data'];
                    $coin->ath = $coin_market_data['ath']['usd'];
                    $coin->atl = $coin_market_data['atl']['usd'];
                    $coin->atl_date = date('Y-m-d h:i:s', strtotime($coin_market_data['atl_date']['usd']));
                    $coin->ath_date = date('Y-m-d h:i:s', strtotime($coin_market_data['ath_date']['usd']));
                    if (is_null($coin->image))
                        $coin->image()->save(new Image(['path' => $image_path]));
                    if ($data['country_origin'] !== "" || $data['country_origin'] !== null) {
                        $country = Country::where("code", $data['country_origin'])->first();
                        if (!is_null($country))
                            $coin->country()->associate($country);
                    }
                    $coin->algorithm = $data['hashing_algorithm'];
                    $coin->price = $coin_market_data['current_price']['usd'];
                    $coin->market_cap = $coin_market_data['market_cap']['usd'];
                    $coin->vol_24 = $coin_market_data['total_volume']['usd'];
                    $coin->high_24 = $coin_market_data['high_24h']['usd'];
                    $coin->low_24 = $coin_market_data['low_24h']['usd'];
                    $coin->price_change_24 = round($coin_market_data['price_change_percentage_24h'], 3);
                    $coin->market_cap_change_percentage_24h = round($coin_market_data['market_cap_change_percentage_24h'], 3);
                    $coin->total_supply = $coin_market_data['total_supply'];
                    $coin->circulating = round(floatval($coin_market_data['circulating_supply']), 3);
                    $coin->block_time = (float)$data['block_time_in_minutes'];
                    $coin->source = 'coingecko';
                    $coin->extra = json_encode([
                        'categories' => $data['categories'],
                        'links' => $data['links'],
                        'genesis_date' => $data['genesis_date'],
                    ]);
                    $coin->details_updated_at = date("Y-m-d H:i:s");
                    $coin->touch();
                    $coin->save();


                } else Log::channel($this->LOG_CHANNEL)->info("$coin_id unsuccessful : " . $data['error']);
        }


    }

    public function sync_additional_coin_data($limit = 50)
    {

        $coins = Coin::where(function ($query) {
            $query->whereMonth('details_updated_at', '<', date('m'))->orWhereNull('details_updated_at');
        })->orderByDesc('market_cap')->orderBy('details_updated_at', 'asc')->limit($limit)->active()->get();
        if ($coins->count())
            foreach ($coins as $coin) {
                $this->get_additional_coin_data($coin->source_id);
            }
    }

    public function sync_exchanges_data($limit = 50)
    {

        $exchange_ids = Exchange::where(function ($query) {
            $query->whereMonth('details_updated_at', '<', date('m'))->orWhereNull('details_updated_at');
        })->orderBy('details_updated_at', 'asc')->limit($limit)->active()->pluck('source_id')->all();
        if ($exchange_ids !== false) {
            foreach ($exchange_ids as $exchange_id) {
                $this->get_exchange_details($exchange_id, false);
                $this->get_exchange_tickers($exchange_id);

            }
        }


    }

    public function get_exchange_ids()
    {
        //
        $data = $this->get_data("/exchanges/list", 'get_exchange_id');
        if ($data !== false) {

            $records = [];
            foreach ($data as $datum) {

                $records [] = [
                    'source_id' => $datum['id'],
                    'name' => $datum['name'],
                    'name_persian' => $datum['name']
                ];


            }
            DB::table('exchanges')->insertOrIgnore($records);

            return $data;
        } else return false;


    }

    public function get_exchange_details($target_exchange_id, $createAdd)
    {
        $data = $this->get_data("/exchanges/$target_exchange_id", 'get_additional_coin_data');
        if ($data !== false) {
            $exchange = null;
            if ($createAdd) {

                $exchange = new Exchange();
                $exchange->source_id = $target_exchange_id;
                $exchange->name = $data['name'];
                $exchange->name_persian = $data['name'];
                $exchange->save();

            } else {
                $exchange = Exchange::where('source_id', $target_exchange_id)->first();
            }
            if ($exchange !== null) {
                $exchange->year_established = $data['year_established'];
                $country = Country::where("name", 'LIKE', '%' . $data['country'] . '%')->first();
                if ($country)
                    $exchange->country()->associate($country);
                $exchange->url = $data['url'];
                $exchange->trust_score_rank = isset($data['trust_score_rank']) ? $data['trust_score_rank'] : null;
                $image_url = str_replace('small', 'large', $data['image']);
                $image_path = $this->download_icon('exchange', $image_url, $target_exchange_id);
                if (is_null($exchange->image))
                    $exchange->image()->save(new Image(['path' => $image_path]));
                $exchange->centralized = $data['centralized'];
                $exchange->extra = json_encode([
                    'facebook_url' => $data['facebook_url'],
                    'reddit_url' => $data['reddit_url'],
                    'telegram_url' => $data['telegram_url'],
                    'other_url_1' => $data['other_url_1'],
                    'other_url_2' => $data['other_url_2'],
                    'twitter_handle' => $data['twitter_handle'],
                ]);
                $exchange->details_updated_at = date("Y-m-d H:i:s");
                $exchange->touch();
                $exchange->save();
            } else Log::channel($this->LOG_CHANNEL)->Info("CoinGecko get_exchange_details for $target_exchange_id unsuccessful because exchange couldn't be created or found.");


        }


    }

    public function get_exchange_tickers($target_exchange_id)
    {
        $exchange = Exchange::where('source_id', $target_exchange_id)->first();
        if ($exchange) {
            $page = 1;
            $load_more = true;
            $target_coin_ids = [];
            while ($load_more) {
                $path = "/exchanges/$target_exchange_id/tickers?page=$page";
                $data = $this->get_data($path, "get_exchange_tickers");
                if ($data !== false) {
                    if (count($data['tickers']) > 0) {
                        foreach ($data['tickers'] as $ticker) {
                            if (isset($ticker['target_coin_id']))
                                $target_coin_ids [] = $ticker['target_coin_id'];
                            if (isset($ticker['coin_id']))
                                $target_coin_ids [] = $ticker['coin_id'];
                        }

                        $page++;
                    } else $load_more = false;


                } else return false;

            }
            $target_coin_ids = array_unique($target_coin_ids);
            $coin_ids = Coin::whereIn('source_id', $target_coin_ids)->pluck('id')->all();
            $exchange->coins()->sync($coin_ids);
            $exchange->save();

        }


    }

    public function get_data($path, $tag)
    {
        $response = Http::get(
            $this->COINGECKO_API_URL . $path
        );
        if ($response->successful()) {

            return $response->json();

        } else {
            Log::channel($this->LOG_CHANNEL)->info("Coingecko $tag unsuccessful : " . $response->body());
            return false;
        }
    }

    public function download_icon($type, $url, $file_name)
    {
        $file_path = parse_url($url)['path'];
        $parsed_url = explode('.', $file_path);
        $file_extension = end($parsed_url);
        return Storage::disk('root-public')->putFileAs("image/icon/$type/large", $url, "$file_name.$file_extension");
    }
}
