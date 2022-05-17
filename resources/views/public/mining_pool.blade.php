{{-- Created by PhpStorm.--}}
{{-- User: Artin--}}
{{-- Date: 6/30/2020--}}
{{-- Time: 6:50 PM--}}
@extends('public.master')
@push('home-page-styles')
    <link rel="stylesheet" href="{{asset('css/mining_pool.css')}}">
@endpush
@php
    $mining_pool_name = isset($mining_pool->name_persian) ? $mining_pool->name_persian : $mining_pool->name;
    $parsed_url = parse_url($mining_pool->homepage_url);
    $parsed_url_scheme =  'http';
    if(isset($parsed_url['scheme'])){
         $parsed_url_scheme = $parsed_url['scheme'];
    }else{
        $parsed_url['host'] = $parsed_url['path'];
        $mining_pool->homepage_url = 'http://'.$mining_pool->homepage_url;
    }
    $rating_stars = floor($mining_pool->rating['Avg']);
    $rating_floating_part = floatval($mining_pool->rating['Avg']) - floor($mining_pool->rating['Avg']);
    $rating_minor = null;
    $rating_minor_number = 0;
    if($rating_floating_part > 0){
        if($rating_floating_part <= 0.3) $rating_minor = "bg-one_third_star";
        elseif ($rating_floating_part <= 0.5) $rating_minor = "bg-half_star";
        elseif ($rating_floating_part <= 0.9) $rating_minor = "bg-three_fourth";
        $rating_minor_number = 1;
    }
    $fee_expanded = explode("\n\n",$mining_pool->fee_expanded);

@endphp
@section('page_title',$mining_pool_name)
@section('body')
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-3 mb-1">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb border-light bg-transparent">
                        <li class="breadcrumb-item"><a href="{{route('mining_pools.index')}}">استخر های استخراج</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$mining_pool_name}}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="jumbotron jumbotron-fluid rounded bg-transparent border-light">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <img
                            src="{{asset(isset($mining_pool->image->path) ? $mining_pool->image->path : 'image/icon/mining_pool/large/default.png' ) }}"
                            class="img-fluid d-block mr-auto ml-auto mr-sm-5" height="150" width="150"
                            alt="لوگو {{$mining_pool_name}}">
                        <div class="d-flex align-items-baseline">
                            <h1 class="mt-4 text-center text-sm-right">{{$mining_pool_name}}</h1>
                            @isset($mining_pool->twitter)
                                <a target="_blank" rel="noreferrer noopener"
                                   href="https://twitter.com/{{Str::after($mining_pool->twitter,'@')}}">
                                    <div class="mr-2 bg-twitter"></div>
                                </a>
                            @endisset
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
                            {{$mining_pool->description->description ?? ''}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row ">
                            <div class="col-md-6">
                                <div class="text-center display-4">
                                    #{{$mining_pool->rank}}
                                </div>
                            </div>
                            <div class="col-md-6">

                                <div style="font-size:20px"
                                     class="mt-3 text-center text-md-left font-weight-bolder text-left">
                                    <a href="{{$mining_pool->homepage_url}}" target="_blank" rel="noreferrer nooperner">
                                    <span
                                        class="ltr {{($parsed_url_scheme === "https") ? 'txt-green' : 'txt-red'}}">{{$parsed_url_scheme . "://"}}</span>{{Str::ucfirst(Str::after($parsed_url['host'],'www.'))}}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <hr data-content="اطلاعات استخر استخراج" class="hr-text">
                        <div class="coin-details-container mt-5">
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="">امکان Merge Mining</div>
                                <div class="{{($mining_pool->can_merge_mining ? "bg-check" : "bg-cross")}}"></div>
                            </div>
                            @if($mining_pool->can_merge_mining)
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <div class="">کوین های Merge Mining</div>
                                    <div class="">
                                        @foreach($mining_pool->merged_mining_coins as $coin)@lang($coin)@if($loop->last)
                                            @continue
                                        @endif, @endforeach
                                    </div>
                                </div>
                            @endif
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="">تقسیم کارمزد تراکنش با ماینر</div>
                                <div class="{{($mining_pool->tx_fee_shared_with_miner ? "bg-check" : "bg-cross")}}"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="">متوسط کارمزد</div>
                                <div class="">{{(float)$mining_pool->average_fee}}</div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="">شیوه پرداخت</div>
                                <div class="">
                                    @foreach($mining_pool->payment_type as $payment_type)@lang($payment_type)@if($loop->last)
                                        @continue
                                    @endif, @endforeach</div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="">امکانات</div>
                                <div class="">
                                    @foreach($mining_pool->pool_features as $feature)@lang($feature)@if($loop->last)
                                        @continue
                                    @endif, @endforeach
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="">موقعیت سرور</div>
                                <div class="">
                                    @foreach($mining_pool->server_locations as $server_locations)@lang($server_locations)@if($loop->last)
                                        @continue
                                    @endif, @endforeach
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
                                    کوین های پشتیبانی شده توسط {{$mining_pool_name}}
                                </button>
                            </h5>
                        </div>

                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                             data-parent="#accordion">
                            <div class="card-body">
                                @foreach($mining_pool->active_coins as $coin)
                                    <div class="coin-item"><a href="{{route('coins.single',$coin->slug)}}">{{__($coin->name)}}</a></div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3 link-white bg-tertiary">
                        <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseTwo"
                                        aria-expanded="true" aria-controls="collapseTwo">
                                    کارمزد استخراج در {{$mining_pool_name}}
                                </button>
                            </h5>
                        </div>

                        <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo"
                             data-parent="#accordion">
                            <div class="card-body text-center">
                                @foreach($fee_expanded as $fee)
                                    <div class="coin-item">{!! nl2br($fee) !!}</div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                    <div class="card mt-3 link-white bg-tertiary">
                        <div class="card-header" id="headingThree">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseThree"
                                        aria-expanded="true" aria-controls="collapseThree">
                                    حداقل پرداخت در {{$mining_pool_name}}
                                </button>
                            </h5>
                        </div>

                        <div id="collapseThree" class="collapse show" aria-labelledby="headingThree"
                             data-parent="#accordion">
                            <div class="card-body text-left ltr">
                                @foreach($mining_pool->minimum_payout as $payout)
                                    <div class="coin-item">
                                        {{$payout}}
                                    </div>
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
@push('home-page-scripts')
    <script type="text/javascript" src="{{asset('js/coin-mixed.js')}}"></script>
@endpush
