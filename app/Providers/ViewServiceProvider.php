<?php
/**
 * Created by PhpStorm.
 * User: Artin
 * Date: 6/30/2020
 * Time: 6:29 PM
 */

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {

        View::composer(
            [
                'public.master',
                'public.coins',
                'public.coin',
                'public.mining_equipment'

            ], 'App\Http\View\Composers\SelectedCurrencyComposer');
    }

}
