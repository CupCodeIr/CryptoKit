<?php
/**
 * Created by PhpStorm.
 * User: Artin
 * Date: 6/30/2020
 * Time: 6:34 PM
 */

namespace App\Http\View\Composers;


use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Currencies;

class SelectedCurrencyComposer
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function compose(View $view)
    {

        $selected_currency = $this->request->cookie('Currency', 'USD');
        if($selected_currency === null)
            $selected_currency = "USD";
        $full_selected_currency = (Currencies::where('code', $selected_currency)->firstOrFail());

        $view->with(compact('full_selected_currency'));

    }

}
