<?php

namespace App\Http\Controllers;

use App\MiningPool;
use App\MyModels\WPPostHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MiningPoolController extends Controller
{
    //

    public function index(Request $request)
    {
        $MP = MiningPool::visible();
        $mining_pools = MiningPool::visible()->with(['description','image']);
        $filterData =  null;
        $filterSeed = [];
        $filterSeed['max_average_fee'] = $MP->max('average_fee');
        $filterSeed['min_average_fee'] = $MP->min('average_fee');
        $filterSeed['pool_features'] = $this->features();
        $filterSeed['payment_types'] = $this->payment_types();
        if($request->has('mp-filter')){
            $filterData = Validator::make($request->all(),[

                'merge-mining' => ['in:yes,on,1,true'],
                'tx-fee-shared' => ['in:yes,on,1,true'],
                'features' => 'array',
                'features.*' => ['min:6','max:20'],
                'payments' => 'array',
                'payments.*' => ['min:3','max:8'],
                'average-fee-min' => ['required_with:average-fee-max','between:' . $filterSeed['min_average_fee'] . ',' . $filterSeed['max_average_fee']],
                'average-fee-max' => ['required_with:average-fee-min','between:' . $filterSeed['min_average_fee'] . ',' . $filterSeed['max_average_fee']],

            ])->validate();
//            dd($filterData);
            if(isset($filterData['features']))
                $mining_pools = $mining_pools->withFeatures($filterData['features']);

            if(isset($filterData['payments']))
                $mining_pools = $mining_pools->withPaymentType($filterData['payments']);

            if(isset($filterData['merge-mining']))
                $mining_pools = $mining_pools->canMergeMining(true);

            if(isset($filterData['tx-fee-shared']))
                $mining_pools = $mining_pools->txFeeSharing(true);

            if(isset($filterData['average-fee-min']))
                $mining_pools = $mining_pools->betweenAverageFee($filterData['average-fee-min'],$filterData['average-fee-max']);


        }
        $mining_pools = $mining_pools->paginate(12);
        $view = view('public.mining_pools',compact('mining_pools','filterSeed'));
        if($filterData !== null){
            $view = $view->with('filterData',$filterData);
        }
        return $view;

    }

    public function single(MiningPool $miningpool){
        abort_unless($miningpool->visibility,404);
        $miningpools_category = config('wordpress.mining_pools_id',null);
        $posts = WPPostHelper::getPosts(3,$miningpools_category);
        $miningpool->load(['active_coins','description','image']);
        return view('public.mining_pool')->with([
            'mining_pool' => $miningpool,
            'posts' => $posts
        ]);
    }

    private function features()
    {
        return [
            'Vardiff',
            'Stratum',
            'Monitoring',
            'Smart Mining',
            'VarFee',
            'NiceHash Compatible',
        ];
    }
    private function payment_types()
    {
        return [
            'PPS',
            'PPLNS',
            'Solo',
            'Score',
            'PPLNT',
            'FPPS',
            'PPLNSG',
            'PPS+',
            'HBPPS',
            'RBPPS',
            'Prop',
            'DGM',
            'HBPPS',
            'P2P',
            'FPPS+',
        ];
    }
}
