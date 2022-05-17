<?php

namespace App\Http\Controllers;


use App\MiningEquipment;
use App\MyModels\WPPostHelper;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class MiningEquipmentController extends Controller
{
    //
    public function index(Request $request)
    {
        $ME = MiningEquipment::visible();
        $mining_equipments = MiningEquipment::visible()->with(['description','image']);
        $filterData =  null;
        $filterSeed = [];
        $filterSeed['max_price'] = $ME->max('cost');
        $filterSeed['min_price'] = $ME->min('cost');
        $filterSeed['max_hashes']= $ME->max('hashes_per_second')/1000000000000;
        $filterSeed['min_hashes'] = $ME->min('hashes_per_second')/1000000000000;
        $filterSeed['max_pc'] = (int)($ME->max('power_consumption'));
        $filterSeed['min_pc'] = (int)($ME->min('power_consumption'));
        $filterSeed['algorithms'] = $ME->select('algorithm')->distinct()->pluck('algorithm');
        $filterSeed['types'] = $ME->select('equipment_type')->distinct()->pluck('equipment_type');
        if($request->has('me-filter')){
            $filterData = Validator::make($request->all(),[

                'algorithms' => 'array',
                'algorithms.*' => ['min:3','max:20'],
                'types' => 'array',
                'types.*' => ['min:2','max:20'],
                'cost-min' => ['required_with:cost-max','between:' . $filterSeed['min_price'] . ',' . $filterSeed['max_price']],
                'cost-max' => ['required_with:cost-min','between:' . $filterSeed['min_price'] . ',' . $filterSeed['max_price']],
                'hash-max' => ['required_with:hash-min','between:' . $filterSeed['min_hashes'] . ',' . $filterSeed['max_hashes']],
                'hash-min' => ['required_with:hash-max','between:' . $filterSeed['min_hashes'] . ',' . $filterSeed['max_hashes']],
                'pc-max' => ['required_with:pc-min','between:' . $filterSeed['min_pc'] . ',' . $filterSeed['max_pc']],
                'pc-min' => ['required_with:pc-max','between:' . $filterSeed['min_pc'] . ',' . $filterSeed['max_pc']],

            ])->validate();
            if($request->has('algorithms'))
                $mining_equipments = $mining_equipments->ofAlgorithm($filterData['algorithms']);
            if($request->has('types'))
                $mining_equipments = $mining_equipments->ofType($filterData['types']);
            if($request->has('cost-min'))
                $mining_equipments = $mining_equipments->betweenPrice($filterData['cost-min'],$filterData['cost-max']);
            if($request->has('hash-min'))
                $mining_equipments = $mining_equipments->betweenHash($filterData['hash-min'] * 1000000000000,$filterData['hash-max'] * 1000000000000);
            if($request->has('pc-min'))
                $mining_equipments = $mining_equipments->betweenPowerConsumption($filterData['pc-min'],$filterData['pc-max']);


        }
        $mining_equipments = $mining_equipments->paginate(12);
        $view = view('public.mining_equipments',compact('mining_equipments','filterSeed'));
        if($filterData !== null){
            $view = $view->with('filterData',$filterData);
        }
        return $view;

    }

    public function single(MiningEquipment $miningequipment){
        abort_unless($miningequipment->visibility,404);
        $miningequipments_category = config('wordpress.mining_equipments_id',null);
        $posts = WPPostHelper::getPosts(3,$miningequipments_category);
        $miningequipment->load(['company','description','image']);
        return view('public.mining_equipment')->with([
            'mining_equipment' => $miningequipment,
            'posts' => $posts
        ]);
    }
}
