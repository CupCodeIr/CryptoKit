{{-- Created by PhpStorm.--}}
{{-- User: Artin--}}
{{-- Date: 6/30/2020--}}
{{-- Time: 6:50 PM--}}
@extends('public.master')
@push('home-page-styles')
    <link rel="stylesheet" href="{{asset('css/mining_equipment.css')}}">
@endpush
@push('home-page-scripts')
    <script type="text/javascript" src="{{asset('js/mining_equipment.js')}}"></script>
@endpush
@php
    $mining_equipment_name = isset($mining_equipment->name_persian) ? $mining_equipment->name_persian : $mining_equipment->name;
    $parsed_url = parse_url($mining_equipment->buy_url);

        $rating_stars = floor($mining_equipment->rating['Avg']);
    $rating_floating_part = floatval($mining_equipment->rating['Avg']) - floor($mining_equipment->rating['Avg']);
        $rating_minor = null;
    $rating_minor_number = 0;
    if($rating_floating_part > 0){
        if($rating_floating_part <= 0.3) $rating_minor = "bg-one_third_star";
        elseif ($rating_floating_part <= 0.5) $rating_minor = "bg-half_star";
        elseif ($rating_floating_part <= 0.9) $rating_minor = "bg-three_fourth";
        $rating_minor_number = 1;
    }


@endphp
@section('page_title',$mining_equipment_name)
@section('body')
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-3 mb-1">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb border-light bg-transparent">
                        <li class="breadcrumb-item"><a href="{{route('mining_equipments.index')}}">تجهیزات ماینینگ</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{$mining_equipment_name}}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="jumbotron jumbotron-fluid rounded bg-transparent border-light">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <img
                            src="{{asset(isset($mining_equipment->image->path) ? $mining_equipment->image->path : 'image/icon/mining_equipment/large/default.png' ) }}"
                            class="img-fluid d-block mr-auto ml-auto mr-sm-5" height="150" width="150"
                            alt="لوگو {{$mining_equipment_name}}">
                        <h1 class="mt-4 text-center text-sm-right">{{$mining_equipment_name}}</h1>
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
                        <div class="coin-description mt-2">
                            {{$mining_equipment->description->description ?? ''}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row ">
                            <div class="col-md-6">
                                <div class="text-center display-4">
                                    #{{$mining_equipment->rank}}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-center">
                                    <a href="{{$mining_equipment->buy_url}}" target="_blank" rel="noreferrer noopener"
                                       class="mt-md-2 mr-md-4 btn btn-warning">
                                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-bag" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M14 5H2v9a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V5zM1 4v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4H1z"/>
                                            <path d="M8 1.5A2.5 2.5 0 0 0 5.5 4h-1a3.5 3.5 0 1 1 7 0h-1A2.5 2.5 0 0 0 8 1.5z"/>
                                        </svg>
                                        لینک فروشگاه
                                    </a>

                                </div>
                            </div>
                        </div>
                        <hr data-content="اطلاعات دستگاه" class="hr-text">
                        <div class="coin-details-container mt-5">
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="">کمپانی ارائه دهنده</div>
                                <div class=""><a target="_blank" rel="noreferrer noopener" href="{{$mining_equipment->company->home_url}}">{{$mining_equipment->company->name}}</a></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="">الگوریتم</div>
                                <div class="">{{$mining_equipment->algorithm}}</div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="">نوع دستگاه</div>
                                <div class="">{{$mining_equipment->equipment_type}}</div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="">نرخ تولید هش</div>
                                <div class="">{{$mining_equipment->hashes_per_second/1000000000000}}TH/s</div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="">مصرف انرژی</div>
                                <div class="">{{number_format($mining_equipment->power_consumption)}}Watt</div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="">قیمت حدودی</div>
                                <div class="">{{number_format((int)$mining_equipment->cost)}} دلار</div>
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

