<?php


namespace App\MyModels;


use Illuminate\Support\Facades\Http;

class GoogleRecaptcha
{

    public static function verify($token)
    {
        $result = false;
        $request = Http::post('https://www.google.com/recaptcha/api/siteverify',[
            'secret' => config('googleRecaptcha.site-key'),
            'response' => $token
        ]);
        if($request->successful()){
            $data = $request->json();
            $result = $data['success'];
        }
        return $result;
    }
}
