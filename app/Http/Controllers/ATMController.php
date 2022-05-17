<?php

namespace App\Http\Controllers;

use App\CryptoATM;
use App\MyModels\WPPostHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ATMController extends Controller
{
    //
    public function index(Request $request)
    {

        $coordinates = null;
        $validator = Validator::make($request->all(), [
            'coordinates' => 'min:3|max:21|bail',
        ]);
        if (!$validator->fails()) {
            $coordinates = $request->query('coordinates');
        }
        $categoriesCollection = CryptoATM::select('category')->distinct()->pluck('category');
        $categories = [];
        foreach ($categoriesCollection as $category)
            $categories[] = [
                'name' => __($category),
                'type' => $category
            ];
        $atm_category = config('wordpress.crypto_map_id',null);
        $posts = WPPostHelper::getPosts(3,$atm_category);
        return view('public.atm_map', compact('coordinates','categories','posts'));
    }

    public function places(Request $request)
    {

        $error = null;
        $features = [];
        if ($request->has('coordinates')) {
            $points = explode(',', $request->query('coordinates'));

            $places = CryptoATM::select(['id', 'lat', 'long', 'category'])->where([
                ['visibility', true],
                ['lat', '>=', $points[0]],
                ['long', '>=', $points[1]],
                ['lat', '<=', $points[2]],
                ['long', '<=', $points[3]]
            ])->get();
            foreach ($places as $place) {

                $features[] = [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [$place['lat'], $place['long']]
                    ],
                    'cat' => $place['category'],
                    'id' => $place['id'],
                ];
            }


        } else $error .= 'مختصات ارسال نشده است<br>';

        return response()->json([
            'error' => $error,
            'data' => [
                'type' => 'FeatureCollection',
                'features' => $features
            ]

        ])->withCallback($request->query('callback'));

    }

    public function place(CryptoATM $cryptoatm)
    {
        if ($cryptoatm->visibility) {
            return response()->json([
                'error' => null,
                'data' => [
                    "name" => $cryptoatm['name'],
                    "place_number" => $cryptoatm['place_number'],
                    "email" => $cryptoatm['email'],
                    "city" => $cryptoatm['city'],
                    "phone" => $cryptoatm['phone'],
                    "postcode" => $cryptoatm['postcode'],
                    "fax" => $cryptoatm['fax'],
                    "state" => $cryptoatm['state'],
                    "opening_hours" => $cryptoatm['opening_hours'],
                    "description" => $cryptoatm['ent_description'],
                    "website" => $cryptoatm['website'],
                    "street" => $cryptoatm['street'],
                    "facebook" => $cryptoatm['facebook'],
                    "twitter" => $cryptoatm['twitter'],
                    "update_on_date" => $cryptoatm['update_on_date'],
                ]
            ]);
        } else abort(404);
    }

}
