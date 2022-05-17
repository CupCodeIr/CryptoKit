<?php

namespace App\Http\Controllers;

use App\MiningCompany;
use App\MyModels\WPPostHelper;
use Illuminate\Support\Str;

class MiningCompanyController extends Controller
{
    //

    public function index()
    {
        $mining_companies = MiningCompany::visible()->with(['description','image'])->paginate(12);
        return view('public.mining_companies',compact('mining_companies'));

    }

    public function single(MiningCompany $miningcompany){
        abort_unless($miningcompany->visibility,404);
        $miningcompanies_category = config('wordpress.mining_companies_id',null);
        $posts = WPPostHelper::getPosts(3,$miningcompanies_category);
        $miningcompany->load(['country','description','image']);
        return view('public.mining_company')->with([
            'mining_company' => $miningcompany,
            'posts' => $posts
        ]);
    }

}
