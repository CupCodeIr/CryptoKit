<?php
/**
 * Created by PhpStorm.
 * User: Artin
 * Date: 6/21/2020
 * Time: 2:17 AM
 */

namespace App\MyModels\GetData;

use App\Country;
use App\Image;
use App\MiningCompany;
use App\MiningEquipment;
use App\MiningPool;
use App\Wallet;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Coin;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CryptoCompare
{
    protected $API_KEY = "93a3709a44fc210f6f176386e095e81b673eff45304b3b3e09114ef041dd41dd";
    protected $API_URL = "https://min-api.cryptocompare.com/data/";
    protected $LOG_CHANNEL = "coin_data";

    public function get_all_coins()
    {
//        $response = Http::get(
//            "https://min-api.cryptocompare.com/data/all/coinlist",
//            [
//                'api_key' => $this->API_KEY
//            ]
//        );
//        if ($response->successful()) {
//            $data = $response->json();
//            if ($data['Response'] === "Success") {
//                $records = [];
//                foreach ($data['Data'] as $datum) {
//                    if (strpos($datum['TotalCoinSupply'], ' ') !== false) {
//                        $datum['TotalCoinSupply'] = str_replace(' ', '', $datum['TotalCoinSupply']);
//                    }
//                    $records [] = [
//                        'name' => $datum['CoinName'],
//                        'symbol' => $datum['Symbol'],
//                        'slug' => $datum['CoinName'] . "-" . $datum['Symbol'],
//                        'circulating' => (isset($datum['TotalCoinsMined']) && $datum['TotalCoinsMined'] < 99999999999999999999 && $datum['TotalCoinsMined'] > 0) ? $datum['TotalCoinsMined'] : 0,
//                        'total_supply' => (isset($datum['TotalCoinSupply']) && $datum['TotalCoinSupply'] !== "N/A") ? (int)$datum['TotalCoinSupply'] : 0,
//                        'source' => 'cryptocompare',
//                        'cryptocompare_id' => $datum['Id'],
//                        'algorithm' => $datum['Algorithm'],
//                        'proof_type' => $datum['ProofType'],
//                        'block_time' => isset($datum['BlockTime']) ? $datum['BlockTime'] : 0,
//                        'block_reward' => isset($datum['BlockReward']) ? $datum['BlockReward'] : 0,
//                        'hash_per_second' => isset($datum['NetHashesPerSecond']) ? $datum['NetHashesPerSecond'] : 0,
//                    ];
//                    DB::table('coins')->upsert($records, ['source_id'],
//                        [
//                            'name',
//                            'symbol',
//                            'circulating',
//                            'total_supply',
//                            'algorithm',
//                            'proof_type',
//                            'block_time',
//                            'block_reward',
//                            'hash_per_second',
//                            ]
//                    );
//
//
//                }
//
//            } else {
//                Log::channel(__METHOD__)->info("CryptoCompare > Message : " . $data['Message']);
//            }
//        } else Log::channel(__METHOD__)->info("CryptoCompare unsuccessful : " . $response->body());
    }

    public function get_all_wallets()
    {
        $data = $this->get_data('wallets/general', __FUNCTION__);
        if ($data !== false) {
            return $data['Data'];
        } else false;
    }

    public function get_all_mining_companies()
    {
        $mining_companies = $this->get_data('mining/companies/general', __FUNCTION__);
        return ($mining_companies !== false) ? $mining_companies['Data'] : false;
    }

    public function get_all_mining_equipment()
    {
        $data = $this->get_data('mining/equipment/general', __FUNCTION__);
        return ($data) ? $data['Data'] : false;
    }

    public function get_all_mining_pools()
    {
        $data = $this->get_data('mining/pools/general', __FUNCTION__);
        return $data ? $data['Data'] : false;
    }

    public function get_trading_signal($coin_id)
    {
        $trading_signal = $this->get_data('tradingsignals/intotheblock/latest', __FUNCTION__, [
            'fsym' => $coin_id
        ], false);
        return $trading_signal ? $trading_signal['Data'] : false;

    }

    public function save_mining_companies()
    {
        $mining_companies = $this->get_all_mining_companies();
        if ($mining_companies) {
            foreach ($mining_companies as $mining_company) {
                $mining_companyRecord = MiningCompany::where('source_id', $mining_company['Id'])->firstOr(function () use ($mining_company) {
                    return MiningCompany::create([
                        'source_id' => $mining_company['Id'],
                        'name' => $mining_company['Name'],
                        'name_persian' => $mining_company['Name'],
                        'slug' => Str::of($mining_company['Name'])->slug('-') . "-" . $mining_company['Id']
                    ]);
                });
                if ($mining_companyRecord->status !== 'deactive') {

                    if(isset($mining_company['AffiliateURL']))
                        $mining_companyRecord->home_url = $mining_company['AffiliateURL'];
                    $mining_companyRecord->rank = $mining_company['SortOrder'];
                    $mining_companyRecord->rating = [
                        'Avg' => $mining_company['Rating']['Avg'],
                        'TotalUsers' => $mining_company['Rating']['TotalUsers'],
                    ];
                    if ($mining_companyRecord->country->code === "OT") {
                        $country = Country::where('name', 'like', '%' . $mining_company['Country'] . '%')->first();
                        $mining_companyRecord->country()->associate($country);
                    }
                    if (is_null($mining_companyRecord->image)) {
                        $image_path = $this->download_icon('mining_company', $mining_company['LogoUrl'], $mining_company['Id']);
                        $image = new Image(['path' => $image_path]);
                        $mining_companyRecord->image()->save($image);
                    }
                    $mining_companyRecord->save();
                }


            }


        } else return false;
    }

    public function save_mining_equipments()
    {
        $mining_equipments = $this->get_all_mining_equipment();
        if ($mining_equipments) {
            foreach ($mining_equipments as $mining_equipment) {
                $mining_equipmentRecord = MiningEquipment::where('source_id', $mining_equipment['Id'])->firstOr(function () use ($mining_equipment) {

                    return MiningEquipment::create([
                        'source_id' => $mining_equipment['Id'],
                        'name' => $mining_equipment['Name'],
                        'name_persian' => $mining_equipment['Name'],
                        'slug' => Str::of($mining_equipment['Name'])->slug('-') . "-" . $mining_equipment['Id'],
                    ]);
                });
                if ($mining_equipmentRecord->status !== 'deactive') {
                    $mining_equipmentRecord->buy_url = isset($mining_equipment['AffiliateURL']) ? $mining_equipment['AffiliateURL'] : null;
                    $mining_equipmentRecord->rank = $mining_equipment['SortOrder'];
                    $mining_equipmentRecord->algorithm = $mining_equipment['Algorithm'];
                    $mining_equipmentRecord->cost = round(floatval($mining_equipment['Cost']), 2);
                    $mining_equipmentRecord->hashes_per_second = (int)$mining_equipment['HashesPerSecond'];
                    $mining_equipmentRecord->power_consumption = (float)$mining_equipment['PowerConsumption'];
                    $mining_equipmentRecord->equipment_type = $mining_equipment['EquipmentType'];
                    $mining_equipmentRecord->rating = [
                        'Avg' => $mining_equipment['Rating']['Avg'],
                        'TotalUsers' => $mining_equipment['Rating']['TotalUsers'],
                    ];

                    if (is_null($mining_equipmentRecord->company)) {
                        $company = MiningCompany::where('source_id', $mining_equipment['ParentId'])->first();
                        $mining_equipmentRecord->company()->associate($company);
                    }
                    if (is_null($mining_equipmentRecord->image)) {
                        $image_path = $this->download_icon('mining_equipment', $mining_equipment['LogoUrl'], $mining_equipment['Id']);
                        $image = new Image(['path' => $image_path]);
                        $mining_equipmentRecord->image()->save($image);
                    }
                    $mining_equipmentRecord->save();
                }


            }
        }
    }

    public function save_first_200_wallet()
    {
        $data = $this->get_all_wallets();
        if ($data !== false) {
            $collection = collect($data)->sortBy('SortOrder')->values()->slice(0, 200);
            foreach ($collection as $wallet) {
                $walletRecord = Wallet::where('source_id', $wallet['Id'])->firstOr(function () use ($wallet) {
                    return Wallet::create([
                        'source_id' => $wallet['Id'],
                        'slug' => Str::of($wallet['Name'])->slug('-') . "-" . $wallet['Id'],
                        'name' => $wallet['Name'],
                        'name_persian' => $wallet['Name']
                    ]);
                });
                if ($walletRecord->status !== 'deactive') {
                    $walletRecord->security = $wallet['Security'];
                    $walletRecord->anonymity = $wallet['Anonymity'];
                    $walletRecord->ease_of_use = $wallet['EaseOfUse'];
                    $walletRecord->has_trading_facilities = $wallet['HasTradingFacilities'];
                    $walletRecord->wallet_features = $wallet['WalletFeatures'];
                    $walletRecord->platform = $wallet['Platforms'];
                    $walletRecord->source_url = $wallet['SourceCodeUrl'];
                    $walletRecord->download_url = $wallet['AffiliateURL'];
                    $walletRecord->rank = $wallet['SortOrder'];
                    $walletRecord->rating = [
                        'Avg' => $wallet['Rating']['Avg'],
                        'total_users' => $wallet['Rating']['TotalUsers'],
                    ];
                    if ($walletRecord->image === null) {
                        $image_path = $this->download_icon('wallet', $wallet['LogoUrl'], $wallet['Id']);
                        $walletRecord->image()->save(new Image(['path' => $image_path]));
                    }
                    $coin_ids = Coin::whereIn('symbol', $wallet['Coins'])->pluck('id')->all();
                    $walletRecord->coins()->sync($coin_ids);
                    $walletRecord->save();
                }

            }
            return true;
        } else false;

    }

    public function save_mining_pools()
    {
        $mining_pools = $this->get_all_mining_pools();
        if ($mining_pools) {
            foreach ($mining_pools as $mining_pool) {
                $mining_poolRecord = MiningPool::where('source_id', intval($mining_pool['Id']))->firstOr(function () use ($mining_pool) {
                    return MiningPool::create([
                        'source_id' => intval($mining_pool['Id']),
                        'name' => $mining_pool['Name'],
                        'name_persian' => $mining_pool['Name'],
                        'slug' => Str::of($mining_pool['Name'])->slug('-') . "-" . $mining_pool['Id']
                    ]);
                });
                if ($mining_poolRecord->status !== 'deactive') {
                    $mining_poolRecord->homepage_url = isset($mining_pool['AffiliateURL']) ? $mining_pool['AffiliateURL'] : null;
                    $mining_poolRecord->can_merge_mining = $mining_pool['MergedMining'];
                    $mining_poolRecord->tx_fee_shared_with_miner = $mining_pool['TxFeeSharedWithMiner'];
                    $mining_poolRecord->twitter = $mining_pool['Twitter'];
                    $mining_poolRecord->average_fee = (float)$mining_pool['AverageFee'];
                    $mining_poolRecord->pool_features = $mining_pool['PoolFeatures'];
                    if ($mining_pool['FeeExpanded'] !== "Unknown")
                        $mining_poolRecord->fee_expanded = $mining_pool['FeeExpanded'];
                    if (in_array("Unknown", $mining_pool['ServerLocations']) === false)
                        $mining_poolRecord->server_locations = $mining_pool['ServerLocations'];
                    if (in_array("Unknown", $mining_pool['PaymentType']) === false)
                        $mining_poolRecord->payment_type = $mining_pool['PaymentType'];
                    $mining_poolRecord->merged_mining_coins = $mining_pool['MergedMiningCoins'];
                    if (strpos($mining_pool['MinimumPayout'],"Unknown") !== false)
                        $mining_poolRecord->minimum_payout = explode(';', $mining_pool['MinimumPayout']);
                    $mining_poolRecord->rank = $mining_pool['SortOrder'];
                    $mining_poolRecord->rating = [
                        'Avg' => $mining_pool['Rating']['Avg'],
                        'TotalUsers' => $mining_pool['Rating']['TotalUsers'],
                    ];
                    if (is_null($mining_poolRecord->image)) {
                        $image_path = $this->download_icon('mining_pool', $mining_pool['LogoUrl'], $mining_pool['Id']);
                        $image = new Image(['path' => $image_path]);
                        $mining_poolRecord->image()->save($image);
                    }
                    $coins = Coin::select('id')->whereIn('symbol', $mining_pool['Coins'])->get();
                    $mining_poolRecord->coins()->sync($coins);
                    $mining_poolRecord->save();
                }
            }
        }
    }

    public function save_trading_signals()
    {
        $coins = Coin::select("id", "symbol")->active()->get()->toArray();
        $records = [];
        foreach ($coins as $coin) {
            $signal = $this->get_trading_signal($coin['symbol']);
            if ($signal) {
                $records [] = [
                    'source_id' => $signal['id'],
                    'coin_id' => $coin['id'],
                    'time' => date('Y-m-d H:i:s', $signal['time']),
                    'inOutVar' => (array)$signal['inOutVar'],
                    'largetxsVar' => (array)$signal['largetxsVar'],
                    'addressesNetGrowth' => (array)$signal['addressesNetGrowth'],
                    'concentrationVar' => (array)$signal['concentrationVar'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ];
            }
        }
        DB::table('trading_signals')->upsert($records, ['source_id'],[
            'time',
            'inOutVar',
            'largetxsVar',
            'addressesNetGrowth',
            'concentrationVar',
            'updated_at'
        ]);

    }

    public function get_data($path, $tag, $param = [], $logError = true)
    {
        $error = "";
        $param = Arr::add($param, 'api_key', $this->API_KEY);
        $response = Http::get(
            $this->API_URL . $path,
            $param
        );
        if ($response->successful()) {
            $data = $response->json();
            if ($data['Response'] === "Success")
                return $data;
            else $error .= "CryptoCompare $tag unsuccessful : data response is not successful : " . $data['Message'];


        } else $error .= "CryptoCompare $tag unsuccessful : " . $response->body();

        if ($error !== "" && $logError) {
            Log::channel($this->LOG_CHANNEL)->info($error);
            return false;
        }
    }

    public function download_icon($type, $url, $file_name)
    {
        $root = "https://www.cryptocompare.com";
        $parsed_url = explode('.', $url);
        $file_extension = end($parsed_url);
        return Storage::disk('root-public')->putFileAs("image/icon/$type/large", $root . $url, "$file_name.$file_extension");
    }

}
