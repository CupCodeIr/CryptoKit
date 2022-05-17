<?php


namespace App\MyModels\GetData;


use App\Country;
use App\meta;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CoinMap
{
    protected $CoinMap_API_URL = "https://coinmap.org/api/v1/venues/";

    public function get_data($tag,$path = "" , $params = [])
    {
        $response = Http::get(
            $this->CoinMap_API_URL . $path, $params
        );
        if ($response->successful()) {

            $data =  $response->json();
            if(isset($data['error'])) return false;
            return $data['venues'];

        } else {
            Log::channel(__METHOD__)->info("CoinMap $tag unsuccessful : " . $response->body());
            return false;
        }
    }

    public function get_venues_periodically()
    {
        $limit = 1000;
        $meta = meta::where('name','coinmap_fetched_data_range')->firstOrCreate(
            ['name' => 'coinmap_fetched_data_range'],
            [
                'value' => ['offset' => 0],
                'group' => 'reminder'

            ]
        );
        $offset = ($meta->value)['offset'];
        $venues = $this->get_data(__METHOD__,'',[
            'offset' => $offset,
            'limit' => $limit,
            'mode' => 'full'
        ]);
        if($venues !== false && empty($venues)){
            $meta->value = ['offset' => 0 ];
            $meta->save();
            return 0;
        }
        if($venues){
            $preparedVenues = [];
            $countries = Country::select('id','code')->get();
            foreach ($venues as $venue) {
                $country = $countries->firstWhere('code',$venue['country']);
                $country_id = ($country !== null) ? $country->id : null;
                $preparedVenues [] = [
                    'source_id' => $venue['id'],
                    'name' => $venue['name'],
                    'lat' => $venue['lat'],
                    'long' => $venue['lon'],
                    'place_number' => $venue['houseno'],
                    'email' => $venue['email'],
                    'city' => $venue['city'],
                    'phone' => $venue['phone'],
                    'postcode' => $venue['postcode'],
                    'fax' => $venue['fax'],
                    'category' => strtolower($venue['category']),
                    'state' => $venue['state'],
                    'opening_hours' => $venue['opening_hours'],
                    'ent_description' => $venue['description'],
                    'website' => $venue['website'],
                    'street' => $venue['street'],
                    'facebook' => $venue['facebook'],
                    'twitter' => $venue['twitter'],
                    'country_id' => $country_id,
                    'create_on_date' => date("Y-m-d H:i:s",$venue['created_on']),
                    'update_on_date' => date("Y-m-d H:i:s",$venue['updated_on']),
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ];
            }
            DB::table('crypto_a_t_m_s')->upsert($preparedVenues, ['source_id'],
                [
                    'name',
                    'lat',
                    'long',
                    'place_number',
                    'email',
                    'city',
                    'phone',
                    'postcode',
                    'fax',
                    'opening_hours',
                    'ent_description',
                    'website',
                    'street',
                    'facebook',
                    'twitter',
                    'twitter',
                    'update_on_date',
                    'updated_at'
                ]
            );
            $meta->value = ['offset' => $offset + count($venues) ];
            $meta->save();
            return true;
        } else return false;

    }





}
