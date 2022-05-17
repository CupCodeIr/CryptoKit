<?php

namespace App\Http\Controllers;

use App\Coin;
use App\WPPOST;
use App\WPPOSTTERM;
use App\MyModels\WPPostHelper;

class CoinController extends Controller
{
    //

    public function index()
    {
        $coins = Coin::where('status','active')->orderByDesc('market_cap')->with(['description','image'])->paginate(12);
        if($coins->currentPage() !== 1)
            abort_unless($coins->count(),404);
        return view('public/coins',compact('coins'));
    }

    public function single(Coin $coin)
    {
        abort_unless($coin->status === "active",404);
        $coin_category = config('wordpress.coins_id',null);
        $posts = WPPostHelper::getPosts(3,$coin_category);
        $coin_rank = (Coin::where('market_cap', '>', $coin->market_cap)->count()) + 1;
        $coin->load(['visible_exchanges.country','trading_signal','visible_wallets','visible_miningPools','description','image']);
        return view('public.coin',compact('coin','coin_rank','posts'));
    }

}
