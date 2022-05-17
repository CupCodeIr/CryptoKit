<?php

namespace App\Http\Controllers;

use App\Coin;
use App\Exchange;
use App\MiningCompany;
use App\MiningEquipment;
use App\MiningPool;
use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class SearchController extends Controller
{
    //

    public function __invoke(Request $request )
    {
        $validator = Validator::make($request->all(), [
            'term' => 'required|min:3|max:60',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'error' => $validator->errors(),
                    'results' => []
                ]

            );
        }
        $term = $request->term;
        $coins = Coin::select(['id', 'name_persian AS text', 'symbol', 'slug'])->active()->where('name', 'like', "$term%")->orWhere('name_persian', 'like', "$term%")->active()->with('image:path,imageable_id')->get();
        $exchanges = Exchange::select(['id', 'name_persian AS text', 'source_id'])->visible()->where('name', 'like', "$term%")->orWhere('name_persian', 'like', "$term%")->visible()->with('image:path,imageable_id')->get();
        $wallets = Wallet::select(['id', 'name_persian AS text', 'slug'])->visible()->where('name', 'like', "$term%")->orWhere('name_persian', 'like', "$term%")->visible()->with('image:path,imageable_id')->get();
        $mining_companies = MiningCompany::select(['id', 'name_persian AS text', 'slug'])->visible()->where('name', 'like', "$term%")->orWhere('name_persian', 'like', "$term%")->visible()->with('image:path,imageable_id')->get();
        $mining_equipments = MiningEquipment::select(['id', 'name_persian AS text', 'slug'])->visible()->where('name', 'like', "$term%")->orWhere('name_persian', 'like', "$term%")->visible()->with('image:path,imageable_id')->get();
        $mining_pools = MiningPool::select(['id', 'name_persian AS text', 'slug'])->visible()->where('name', 'like', "$term%")->orWhere('name_persian', 'like', "$term%")->visible()->with('image:path,imageable_id')->get();
        foreach ($coins as $coin) {
            $coin->slug = route('coins.single',$coin->slug);
        }
        foreach ($exchanges as $exchange) {
            $exchange->slug = route('exchanges.single',$exchange->source_id);
        }
        foreach ($wallets as $wallet) {
            $wallet->slug = route('wallets.single',$wallet->slug);
        }
        foreach ($mining_companies as $mining_company) {
            $mining_company->slug = route('mining_companies.single',$mining_company->slug);
        }
        foreach ($mining_equipments as $mining_equipment) {
            $mining_equipment->slug = route('mining_equipments.single',$mining_equipment->slug);
        }
        foreach ($mining_pools as $mining_pool) {
            $mining_pool->slug = route('mining_pools.single',$mining_pool->slug);
        }
        $result = [];
        if($coins->count() > 0) $result[] =                 [
            'text' => 'کوین ها',
            'children' => $coins
        ];
        if($exchanges->count() > 0) $result[] =                 [
            'text' => 'صرافی ها',
            'children' => $exchanges
        ];
        if($wallets->count() > 0)  $result[] =              [
            'text' => 'کیف پول ها',
            'children' => $wallets
        ];
        if($mining_companies->count() > 0) $result[] =                 [
            'text' => 'کمپانی های استخراج',
            'children' => $mining_companies
        ];
        if($mining_equipments->count() > 0) $result[] =  [
            'text' => 'تجهیزات استخراج',
            'children' => $mining_equipments
        ];
        if($mining_pools->count() > 0) $result[] = [
            'text' => 'استخرهای استخراج',
            'children' => $mining_pools
        ];
        return response()->json([
            'status' => true,
            'error' => null,
            'results' => $result,
            'pagination' => [
                'more' => false
            ]
        ]);
    }
}
