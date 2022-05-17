{{-- Created by PhpStorm.--}}
{{-- User: Artin--}}
{{-- Date: 6/30/2020--}}
{{-- Time: 6:50 PM--}}
@extends('public.master')
@push('home-page-styles')
    <link rel="stylesheet" href="{{asset('css/wallet.css')}}">
@endpush
@push('home-page-scripts')
    <script type="text/javascript" src="{{asset('js/wallet.js')}}"></script>
@endpush
@php
    $wallet_name = isset($wallet->name_persian) ? $wallet->name_persian : $wallet->name;
    $parsed_url = parse_url($wallet->download_url);

    $rating_stars = floor($wallet->rating['Avg']);
    $rating_floating_part = floatval($wallet->rating['Avg']) - floor($wallet->rating['Avg']);
        $rating_minor = null;
    $rating_minor_number = 0;
    if($rating_floating_part > 0){
        if($rating_floating_part <= 0.3) $rating_minor = "bg-one_third_star";
        elseif ($rating_floating_part <= 0.5) $rating_minor = "bg-half_star";
        elseif ($rating_floating_part <= 0.9) $rating_minor = "bg-three_fourth";
        $rating_minor_number = 1;
    }

@endphp
@section('page_title',$wallet_name)
@section('body')
    <div class="container">
    <div class="row">
        <div class="col-md-12 mt-3 mb-1">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb border-light bg-transparent">
                    <li class="breadcrumb-item"><a href="{{route('wallets.index')}}">کیف پول ها</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{$wallet_name}}</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="jumbotron jumbotron-fluid rounded bg-transparent border-light">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <img
                        src="{{asset(isset($wallet->image->path) ? $wallet->image->path : 'image/icon/wallet/large/default.png' ) }}"
                        class="img-fluid d-block mr-auto ml-auto mr-sm-5" height="150" width="150"
                        alt="لوگو {{$wallet_name}}">
                    <div class="d-flex align-items-baseline">
                        <h1 class="mt-4 text-center text-sm-right">{{$wallet_name}}</h1>
                        @if(filter_var($wallet->source_url, FILTER_VALIDATE_URL) !== FALSE)
                            <a target="_blank" rel="noreferrer noopener" href="{{$wallet->source_url}}"><div class="mr-2 bg-github"></div></a>
                        @endif
                    </div>
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

                    <div class="coin-description">
                        {{$wallet->description->description ?? ''}}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row ">
                        <div class="col-md-6">
                            <div data-toggle="tooltip" data-placement="bottom" title="رتبه کیف پول" class="text-center display-4">
                                #{{$wallet->rank}}
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div style="font-size:18px"
                                 class="mt-3 text-break text-center text-md-left font-weight-bolder text-left">
                                <a href="{{$wallet->download_url}}" target="_blank" rel="noreferrer nooperner">
                                    <span
                                        class="ltr {{($parsed_url['scheme'] === "https") ? 'txt-green' : 'txt-red'}}">{{$parsed_url['scheme'] . "://"}}</span>{{Str::ucfirst(Str::after($parsed_url['host'],'www.'))}}
                                </a>
                            </div>
                        </div>
                    </div>
                    <hr data-content="اطلاعات کیف پول" class="hr-text">
                    <div class="coin-details-container mt-5">
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div class="">سطح امنیت</div>
                            <div class="">@lang($wallet->security)</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div class="">سطح ناشناسی</div>
                            <div class="@lang("wallet." . $wallet->anonymity)"></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div class="">آسانی استفاده</div>
                            <div
                                class="">@lang($wallet->ease_of_use)</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div class="">پلتفرم ها</div>
                            <div
                                class="d-flex">
                                @foreach($wallet->platform as $platform)
                                    <div data-toggle="tooltip" data-placement="right" title="@lang('wallet.' . $platform . " tip")" class="m-1 @lang('wallet.' . $platform)"></div>
                                @endforeach
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div class="">امکانات کیف پول</div>
                            <div
                                class="">
                                @foreach($wallet->wallet_features as $feature)
                                    @lang($feature)
                                    @if(!$loop->last)
                                        ,
                                        @endif

                                @endforeach
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div class="">امکان معامله</div>
                            <div
                                class="{{$wallet->has_trading_facilities ? "bg-check" : "bg-cross"}}"></div>
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
                                    کوین های پشتیبانی شده توسط {{$wallet_name}}
                                </button>
                            </h5>
                        </div>

                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                             data-parent="#accordion">
                            <div class="card-body">
                                @foreach($wallet->coins as $coin)
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

