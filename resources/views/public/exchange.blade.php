{{-- Created by PhpStorm.--}}
{{-- User: Artin--}}
{{-- Date: 6/30/2020--}}
{{-- Time: 6:50 PM--}}
@extends('public.master')
@push('home-page-styles')
    <link rel="stylesheet" href="{{asset('css/exchange.css')}}">
@endpush
@push('home-page-scripts')
    <script type="text/javascript" src="{{asset('js/exchange.js')}}"></script>
@endpush
@php
    $remove_empty_items = function($array){
        $cleaned_array = [];
    foreach ($array as $key => $item){
        if($item !== "")
            $cleaned_array[$key] = $item;
    }
            return $cleaned_array;

    };
$exchange_extra = json_decode($exchange->extra);
$exchange_social = $remove_empty_items([
        'twitter_screen_name' => $exchange_extra->twitter_handle,
        'facebook_url' => $exchange_extra->facebook_url,
        'other_url_2' => $exchange_extra->other_url_2,
        'other_url_1' => $exchange_extra->other_url_1,
        'telegram_url' => $exchange_extra->telegram_url,
        'reddit_url' => $exchange_extra->reddit_url,
]);
    $exchange_name = isset($exchange->name_persian) ? $exchange->name_persian : $exchange->name;
    $parsed_url = parse_url($exchange->url);

@endphp
@section('page_title',$exchange_name)
@section('body')
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-3 mb-1">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb border-light bg-transparent">
                        <li class="breadcrumb-item"><a href="{{route('exchanges.index')}}">صرافی ها</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$exchange_name}}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="jumbotron jumbotron-fluid rounded bg-transparent border-light">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <img
                            src="{{asset(isset($exchange->image->path) ? $exchange->image->path : 'image/icon/exchange/large/default.png' ) }}"
                            class="img-fluid d-block mr-auto ml-auto mr-sm-5" height="150" width="150"
                            alt="لوگو {{$exchange_name}}">
                        <h1 class="mt-4 text-center text-sm-right">{{$exchange_name}}</h1>
                        <div class="coin-description">
                            {{$exchange->description->description ?? ''}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row ">
                            <div class="col-md-6">
                                <div class="text-center display-4">
                                    #{{$exchange->trust_score_rank}}
                                </div>
                            </div>
                            <div class="col-md-6">

                                <div style="font-size:20px"
                                     class="mt-3 text-break text-center text-md-left font-weight-bolder text-left">
                                    <a href="{{$exchange->url}}" target="_blank" rel="noreferrer nooperner">
                                    <span
                                        class="ltr {{($parsed_url['scheme'] === "https") ? 'txt-green' : 'txt-red'}}">{{$parsed_url['scheme'] . "://"}}</span>{{Str::ucfirst(Str::after($parsed_url['host'],'www.'))}}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <hr data-content="اطلاعات صرافی" class="hr-text">
                        <div class="coin-details-container mt-5">
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="">کشور</div>
                                <div class="">@lang("countries.". $exchange->country->code)</div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="">نوع صرافی</div>
                                <div class="">{{($exchange->centralized)? "متمرکز (CEX)" : "نامتمرکز (DEX)"}}</div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="">سال تاسیس</div>
                                <div class="">                                {{$exchange->year_established ?? "نامشخص"}}
                                    @isset($exchange->year_established)
                                    ~ {{ \Morilog\Jalali\CalendarUtils::toJalali($exchange->year_established,0,0)[0]}}
                                    @endisset</div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="">شبکه های اجتماعی</div>
                                <div class="d-flex">
                                    @empty(!$exchange_social)
                                        @isset($exchange_social['twitter_screen_name'])
                                            <a class="mr-2" href="https://twitter.com/{{$exchange_social['twitter_screen_name']}}"
                                               target="_blank">
                                                <div class="bg-twitter"></div>
                                            </a>
                                        @endisset
                                        @isset($exchange_social['facebook_url'])
                                            <a class="mr-2" href="{{$exchange_social['facebook_url']}}" target="_blank">
                                                <div class="bg-facebook"></div>
                                            </a>
                                        @endisset
                                        @isset($exchange_social['telegram_url'])
                                            <a class="mr-2" href="{{$exchange_social['telegram_url']}}" target="_blank">
                                                <div class="bg-telegram"></div>
                                            </a>
                                        @endisset
                                        @isset($exchange_social['reddit_url'])
                                            <a class="mr-2" href="{{$exchange_social['reddit_url']}}" target="_blank">
                                                <div class="bg-reddit"></div>
                                            </a>
                                        @endisset
                                        @isset($exchange_social['other_url_1'])
                                            <a class="mr-2" href="{{$exchange_social['other_url_1']}}" target="_blank">
                                                <div class="bg-

"></div>
                                            </a>
                                        @endisset
                                    @endempty
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="accordion">
                    <div class="card mt-3 link-white bg-tertiary">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"
                                        aria-expanded="true" aria-controls="collapseOne">
                                    کوین های پشتیبانی شده توسط {{$exchange_name}}
                                </button>
                            </h5>
                        </div>

                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                             data-parent="#accordion">
                            <div class="card-body">
                                @foreach($exchange->active_coins_ordered as $coin)
                                    <div class="coin-item"><a href="{{route('coins.single',$coin->slug)}}">{{__($coin->name)}}</a></div>
                                @endforeach
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

