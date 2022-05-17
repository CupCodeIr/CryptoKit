<?php

namespace App\Http\Controllers;

use App\Coin;
use App\Currencies;
use App\MyModels\WPPostHelper;
use App\WPPOST;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //

    public function index(Request $request)
    {
        $posts = WPPostHelper::getPosts(3);
        $coins = Coin::active()->orderBy('market_cap','desc')
            ->take(100)
            ->get();
        $selected_currency = $request->cookie('Currency','USD');
        if($selected_currency == null)
            $selected_currency = "USD";
        $full_selected_currency = (Currencies::where('code',$selected_currency)->firstOrFail());
        return view('public.home',compact('coins','full_selected_currency','posts'));
    }
}
