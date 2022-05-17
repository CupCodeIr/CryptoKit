<?php

namespace App\Http\Controllers;

use App\MyModels\WPPostHelper;
use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WalletController extends Controller
{
    //


    public function index(Request $request)
    {
        $wallets = Wallet::visible()->orderBy('anonymity', 'asc')->orderBy('ease_of_use', 'asc')->orderByDesc('rank')->with(['description','image']);
        $filterData = null;
        if ($request->has('w-filter')) {
            $filterData = $request->validate([
                'security' => ['min:8', 'max:21', 'alpha_dash'],
                'platforms' => ['array'],
                'platforms.*' => ['min:3', 'max:16', 'alpha_dash'],
                'features' => ['array'],
                'features.*' => ['min:11', 'max:26', 'alpha_dash'],
                'trading_facility' => ['in:yes,on,1,true'],
            ]);
            if (isset($filterData['security']))
                $wallets = $wallets->withSecurity( Str::title(str_replace('-', ' ', $filterData['security'])));
            if (isset($filterData['trading_facility']))
                $wallets = $wallets->hasTradingFacility(true);
            else
                $wallets = $wallets->hasTradingFacility(false);

            if (isset($filterData['platforms'])) {
                $sanitizedPlatforms = [];
                foreach ($filterData['platforms'] as $platform){
                    $sanitizedPlatforms[] = str_replace('-',' ',$platform);
                }
                $wallets = $wallets->withPlatforms( $sanitizedPlatforms);
            }
            if (isset($filterData['features'])) {
                $sanitizedFeatures = [];
                foreach ($filterData['features'] as $feature){
                    $sanitizedFeatures[] = str_replace('_',' ',$feature);
                }
                $wallets = $wallets->withFeatures( $sanitizedFeatures);
            }


        }
        $wallets = $wallets->paginate(12);
        $platforms = $this->getPlatforms();
        $features = $this->getFeatures();
        $securities = $this->getSecurities();
        if ($wallets->currentPage() !== 1)
            abort_unless($wallets->count(), 404);
        $view = view('public/wallets', compact('wallets', 'features', 'platforms', 'securities','filterData'));
        if($filterData !== null)
            $view->with('filterData',$filterData);
        return $view ;
    }

    public function single(Wallet $wallet)
    {
        abort_unless($wallet->visibility, 404);
        $wallet_category = config('wordpress.wallets_id',null);
        $posts = WPPostHelper::getPosts(3,$wallet_category);
        $wallet->load(['coins','description','image']);
        return view('public.wallet', compact('wallet','posts'));
    }

    private function getPlatforms()
    {
        return [
            'Android' => 'اندروید',
            'iOS' => 'آی او اس',
            'Web' => 'وب',
            'Windows' => 'ویندوز',
            'Mac-OS' => 'مک او اس',
            'Chrome-Extension' => 'افزونه کروم',
            "Hardware" => "سخت افزار",
            "Linux" => "لینوکس",
            "Windows-Phone" => "ویندوز فون",
        ];
    }

    private function getFeatures()
    {
        return [

            "Multi-Signature" => "چند امضایی",
            "2_Factor_Authentication" => "تایید دو مرحله ای",
            "Hierarchical_Deterministic" => "ارائه آدرس 12 کلمه ای",
            "Open_Source" => "متن باز",
        ];

    }

    private function getSecurities()
    {
        return [
            'personal' => 'شخصی',
            'third-party-encrypted' => 'کدگذاری شده توسط شخص ثالث',
            'third-party' => 'شخص ثالث',
        ];
    }
}
