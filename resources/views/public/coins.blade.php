{{-- Created by PhpStorm.--}}
{{-- User: Artin--}}
{{-- Date: 6/28/2020--}}
{{-- Time: 3:29 PM--}}

@extends('public.master')
@push('home-page-styles')
    <link rel="stylesheet" href="{{asset('css/entity-index.css')}}">
@endpush
@php
    $exchange_exist = isset($exchange);
$page_title = $exchange_exist ? "کوین های صرافی " . (isset($exchange->name_persian) ? $exchange->name_persian :$exchange->name ) : "کوین ها";


            function number_clean($num){

              //remove zeros from end of number ie. 140.00000 becomes 140.
              $clean = rtrim($num, '0');
              //remove zeros from front of number ie. 0.33 becomes .33
              //$clean = ltrim($clean, '0');
              //remove decimal point if an integer ie. 140. becomes 140
              $clean = rtrim($clean, '.');

              return $clean;
            }

@endphp
@section('page_title',$page_title)
@section('body')
    <div class="row no-gutters">
        <div class="col-md-12">
            <h1 class="mt-5 text-center">فهرست تمامی {{$page_title}}</h1>
        </div>
    </div>
    <div class="row no-gutters">
        <div class="col-md-12 p-5">
            @php
                $total_items = $coins->count();
                $item_per_row =  4;
            @endphp
            <div class="card-columns masonry-container">
                @foreach($coins as $key => $coin)

                    <div class="card bg-transparent border-light">
                        <img width="250" height="250" class="d-block mr-auto ml-auto p-5"
                             src="{{asset(isset($coin->image->path) ? $coin->image->path : 'image/icon/coin/large/coin-default.png' ) }}"
                             alt="لوگو {{__($coin->name)}}">
                        <div class="card-body">
                            <h2 class="card-title"><a
                                        href="{{route('coins.single',$coin->slug)}}">{{__($coin->name)}}</a>
                            </h2>
                            @php
                                $price = number_clean(number_format($coin->price * $full_selected_currency->usd_buy_price,$full_selected_currency->floating_point));
                            @endphp
                            <div class="card-text d-inline-block coin-meta">

                                    <div class="meta-item">{{Str::upper($coin->symbol)}}</div>
                                    <div class="meta-item">{{$full_selected_currency->symbol . $price }}</div>
                                <div @if($coin->price_change_24 < 0) class="meta-item ltr text-danger"
                                    @else class="meta-item" style="color: green;" @endif>{{number_format($coin->price_change_24,2)}}%
                                </div>
                            </div>
                            <div class="card-text mb-2 mt-2">
                                {{Str::limit(strip_tags(($coin->description->description ?? '')),300,' ...')}}
                            </div>
                            <p class="card-text">
                                <small class="text-muted">بروزرسانی در {{jdate($coin->updated_at)->ago()}}</small>
                            </p>
                        </div>
                    </div>
                @endforeach

            </div>
            <div class="pagination-container mt-3">{{$coins->links()}}</div>
        </div>
    </div>

@endsection
@push('home-page-scripts')
    <script type="text/javascript" src="{{asset('js/entity-index.js')}}"></script>
@endpush
