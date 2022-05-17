<?php

namespace App\Http\Controllers;


class SetCurrencyController extends Controller
{
    //
    public function __invoke(\App\Currencies $currency)
    {
        $cookie = cookie('Currency',$currency->code,525666);
        return redirect()->back()->withCookie($cookie);

        // TODO: Implement __invoke() method.
    }
}
