<?php

namespace App\Http\Controllers;

use App\Country;
use App\Exchange;
use App\MyModels\WPPostHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExchangeController extends Controller
{
    //

    public function index(Request $request)
    {
        $exchanges = Exchange::visible()->orderBy('trust_score_rank', 'asc')->with(['description','image']);
        $countries = Country::select('id', 'name','code')->visible()->get();
        $filterData = null;
        if ($request->has('e-filter')) {
            $filterData = $request->validate([
                'type' => ['in:yes,on,1,true'],
                'countries' => ['array'],
                'countries.*' => ['numeric'],
            ]);
            if ($request->has('type')) {
                $type = ($request->type == 'on' || $request->type == '1' || $request->type == 'true' || $request->type === 'yes');
                $exchanges = $exchanges->ofType($type);
            }
            else {
                $exchanges = $exchanges->ofType(false);
            }

            if ($request->has('countries'))
                $exchanges = $exchanges->ofCountries($request->countries);


        }
        $exchanges = $exchanges->paginate(12);
        $view = view('public.exchanges', compact('exchanges', 'countries'));
        if($filterData !== null)
            $view = $view->with('filterData',$filterData);
        return $view;

    }

    public function single(Exchange $exchange)
    {
        abort_unless(Str::contains($exchange->status, 'show'), 404);
        $exchanges_category = config('wordpress.exchanges_id',null);
        $posts = WPPostHelper::getPosts(3,$exchanges_category);
        $exchange->load(['active_coins_ordered','description','image']);
        return view('public.exchange', compact('exchange','posts'));
    }

}
