{{-- Created by PhpStorm.--}}
{{-- User: Artin--}}
{{-- Date: 6/30/2020--}}
{{-- Time: 6:50 PM--}}
@extends('public.master')
@push('home-page-styles')
    <link rel="stylesheet" href="{{asset('css/mining_company.css')}}">
@endpush
@push('home-page-scripts')
    <script type="text/javascript" src="{{asset('js/mining_company.js')}}"></script>
@endpush
@php
    $mining_company_name = isset($mining_company->name_persian) ? $mining_company->name_persian : $mining_company->name;

$parsed_url = parse_url($mining_company->home_url);
    $parsed_url_scheme =  'http';
    if(isset($parsed_url['scheme'])){
         $parsed_url_scheme = $parsed_url['scheme'];
    }else{
        $parsed_url['host'] = $parsed_url['path'];
        $mining_company->home_url = 'http://'.$mining_company->home_url;
    }


    $rating_stars = floor($mining_company->rating['Avg']);
    $rating_floating_part = floatval($mining_company->rating['Avg']) - floor($mining_company->rating['Avg']);
        $rating_minor = null;
    $rating_minor_number = 0;
    if($rating_floating_part > 0){
        if($rating_floating_part <= 0.3) $rating_minor = "bg-one_third_star";
        elseif ($rating_floating_part <= 0.5) $rating_minor = "bg-half_star";
        elseif ($rating_floating_part <= 0.9) $rating_minor = "bg-three_fourth";
        $rating_minor_number = 1;
    }


@endphp
@section('page_title',$mining_company_name)
@section('body')
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-3 mb-1">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb border-light bg-transparent">
                        <li class="breadcrumb-item"><a href="{{route('mining_companies.index')}}">کمپانی های ماینینگ</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$mining_company_name}}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="jumbotron jumbotron-fluid rounded bg-transparent border-light">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <img
                            src="{{asset(isset($mining_company->image->path) ? $mining_company->image->path : 'image/icon/mining_company/large/default.png' ) }}"
                            class="img-fluid d-block mr-auto ml-auto mr-sm-5" height="150" width="150"
                            alt="لوگو {{$mining_company_name}}">
                        <h1 class="mt-4 text-center text-sm-right">{{$mining_company_name}}</h1>
                        <div class="star-rating d-flex">
                            @for($i=1; $i <=$rating_stars;$i++)
                                <div class="bg-star"></div>
                            @endfor
                            @isset($rating_minor)
                                <div class="{{$rating_minor}}"></div>
                            @endisset
                            @for($i=1; $i <= 5 - $rating_stars - $rating_minor_number;$i++)
                                <div class="bg-unstar"></div>
                            @endfor

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row ">
                            <div class="col-md-6">
                                <div class="text-center display-4">
                                    #{{$mining_company->rank}}
                                </div>
                            </div>
                            <div class="col-md-6">

                                <div style="font-size:20px"
                                     class="mt-3 text-center text-md-left font-weight-bolder text-left">
                                    <a href="{{$mining_company->home_url}}" target="_blank" rel="noreferrer nooperner">
                                    <span
                                        class="ltr {{($parsed_url_scheme === "https") ? 'txt-green' : 'txt-red'}}">{{$parsed_url_scheme . "://"}}</span>{{Str::ucfirst(Str::after($parsed_url['host'],'www.'))}}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <hr data-content="اطلاعات صرافی" class="hr-text">
                        <div class="coin-details-container mt-5">
                            <div class="coin-description">
                                {{$mining_company->description->description ?? ''}}
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="">کشور</div>
                                <div class="">@lang("countries.". $mining_company->country->code)</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        @empty(!$posts)
            <div class="row my-3 no-gutters">
                <div class="col-md-12 text-center">
                    <h2>آخرین نوشته های وبلاگ</h2>
                </div>
            </div>
            <div class="row text-secondary no-gutters ">
                @foreach($posts as $post)
                    <div class="col-md-4 py-2 p-1">
                        <article class="card h-100">
                            <img class="card-img-top" src="{{$post->image['url'] ?? ''}}" alt="{{$post->post_title ?? ''}}">
                            <div class="card-body">
                                <h3 class="card-title"><a class="text-dark" href="{{$post->guid}}">{{$post->post_title}}</a></h3>
                                <p class="card-text">{{Str::limit(strip_tags($post->post_content),250,'...')}}</p>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">آخرین بروزرسانی {{\Morilog\Jalali\Jalalian::forge($post->post_date)->ago()}}</small>
                            </div>
                        </article>
                    </div>
                @endforeach

            </div>
        @endempty
    </div>
@endsection

