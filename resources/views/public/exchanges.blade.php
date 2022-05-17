{{-- Created by PhpStorm.--}}
{{-- User: Artin--}}
{{-- Date: 6/28/2020--}}
{{-- Time: 3:29 PM--}}

@extends('public.master')
@push('home-page-styles')
    <link rel="stylesheet" href="{{asset('css/entity-index.css')}}">
@endpush
@push('home-page-scripts')
    <script type="text/javascript" src="{{asset('js/entity-index.js')}}"></script>
    <script>
        $(document).ready(function () {


            $('#platforms-filter').select2({
                placeholder: 'کشور ها',
                dir: 'rtl',
                width: '100%',
                language: 'fa',


            });
        });

    </script>
@endpush
@php
    $coin_exist = isset($coin);
    $page_title = $coin_exist ? "صرافی های " . __($coin->name) : "صرافی ها";
@endphp
@section('page_title',$page_title)
@section('body')
    <div class="row no-gutters">
        <div class="col-md-12">
            <h1 class="mt-5 text-center">فهرست تمامی {{$page_title}} </h1>
        </div>
    </div>
    <div class="row no-gutters">
        <div class="col-md-10 order-last pt-5 pl-5">
            @php
                $total_items = $exchanges->count();
                $item_per_row =  4;
            @endphp
            @if($total_items > 0)
                <div class="card-columns masonry-container">
                    @foreach($exchanges as $key => $exchange)

                        <div class="card bg-transparent border-light">
                            <img width="250" height="250" class="d-block mr-auto ml-auto img-fluid p-5"
                                 src="{{asset(isset($exchange->image->path) ? $exchange->image->path : 'image/icon/exchange/large/default.png' ) }}"
                                 alt="لوگو {{ $exchange->name_persian ?? $exchange->name}}">
                            <div class="card-body">
                                <h2 class="card-title"><a
                                        href="{{route('exchanges.single',$exchange->source_id)}}">{{$exchange->name_persian ?? $exchange->name}}</a>
                                </h2>
                                <div class="card-text d-inline-block coin-meta">

                                    {{--                                    <div class="meta-item">{{Str::upper($exchange->symbol)}}</div>--}}
                                    {{--                                    <div class="meta-item">{{$full_selected_currency->symbol . $price }}</div>--}}
                                    {{--                                <div @if($coin->price_change_24 < 0) class="meta-item ltr text-danger"--}}
                                    {{--                                    @else class="meta-item" style="color: green;" @endif>{{number_format($coin->price_change_24,2)}}%--}}
                                    {{--                                </div>--}}
                                </div>
                                <div class="card-text mb-2 mt-2">
                                    {{Str::limit(strip_tags($exchange->description->description ?? ''),300,' ...')}}
                                </div>
                                <p class="card-text">
                                    <small class="text-muted">بروزرسانی
                                        در {{jdate($exchange->updated_at)->ago()}}</small>
                                </p>
                            </div>
                        </div>
                    @endforeach

                </div>
                <div class="pagination-container mt-3">{{$exchanges->withQueryString()->links()}}</div>
            @else
                <h2 class="text-center">چیزی یافت نشد!</h2>
            @endif
        </div>
        <div class="col-md-2 order-first pt-5">
            <div id="accordion">
                <div class="card bg-none border-0">
                    <div class="card-header bg-none border-0 pt-0" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-outline-light w-100" data-toggle="collapse"
                                    data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                فیلتر نتایج
                            </button>
                        </h5>
                    </div>

                    <div id="collapseOne" class="collapse @if(isset($filterData)) show @else hide @endif"
                         aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <form>
                                <input hidden name="e-filter" value="1">
                                <div class="d-flex flex-column">
                                    <div class="my-2">
                                        <label class="switch">
                                            <input name="type"
                                                   @php
                                                       if(isset($filterData)){
    if(isset($filterData['type'])){
        echo 'checked';
    }
}else{
    echo 'checked';
}
                                                   @endphp


                                                   type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                        صرافی متمرکز
                                    </div>
                                    <div id="platforms-filter-container" class="my-1">
                                        <select id="platforms-filter" class="wallet-filter-select" name="countries[]"
                                                multiple="multiple">
                                            @foreach($countries as $country)
                                                <option
                                                    @isset($filterData['countries'])
                                                    @foreach($filterData['countries'] as $selectedCountry)
                                                    @if((int)$selectedCountry === $country->id)
                                                    selected
                                                    @break
                                                    @endif
                                                    @endforeach
                                                    @endisset
                                                    value="{{$country->id}}">{{__('countries.' . $country->code)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="my-1">
                                        <input class="btn btn-success w-100" type="submit" value="اعمال فیلتر">
                                    </div>
                                    <div class="my-1">
                                        <a href="{{url()->current()}}" class="btn btn-danger w-100">حذف فیلتر</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- end card body -->
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

