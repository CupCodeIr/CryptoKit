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
            $('#security-filter').select2({
                placeholder: 'سطح امنیت کیف پول',
                dir: 'rtl',
                language: 'fa',
                width: '100%',
                minimumResultsForSearch: Infinity,
            });
            @isset($filterData['trading_facility'])
            $('input[name="trading_facility"]').trigger('click');
            @endisset

            $('#platforms-filter').select2({
                templateResult: platformsSelectIconEmbed,
                placeholder: 'پلتفرم ها',
                dir: 'rtl',
                width: '100%',
                language: 'fa',


            });
            $('#features-filter').select2({
                placeholder: 'امکانات',
                dir: 'rtl',
                language: 'fa',
                width: '100%',
                selectionCssClass: ':all:'

            });
        });

        function platformsSelectIconEmbed(state) {
            return $('<div class="d-flex"><div class="bg-' + state.id + '"></div><div class="mr-1">' + state.text + '</div></div>');

        }

    </script>
@endpush
@section('page_title','کیف پول ها')
@section('body')
    <div class="row no-gutters">
        <div class="col-md-12">
            <h1 class="mt-5 text-center">فهرست تمامی کیف پول ها </h1>
        </div>
    </div>
    <div class="row no-gutters">
        <div class="col-md-10 order-last pt-5 px-2 pl-md-3">
            @php
                $total_items = $wallets->count();
                $item_per_row =  4;
            @endphp
            @if($total_items > 0)
                <div class="card-columns masonry-container">
                    @foreach($wallets as $key => $wallet)

                        <div class="card bg-transparent border-light">
                            <img width="250" height="250" class="rounded-image d-block mr-auto ml-auto img-fluid p-5"
                                 src="{{asset(isset($wallet->image->path) ? $wallet->image->path : 'image/icon/wallet/large/default.png' ) }}"
                                 alt="لوگو {{ $wallet->name_persian ?? $wallet->name}}">
                            <div class="card-body">
                                <h2 class="card-title"><a
                                        href="{{route('wallets.single',$wallet->slug)}}">{{$wallet->name_persian ?? $wallet->name}}</a>
                                </h2>
                                <div class="card-text d-inline-block coin-meta">
                                </div>
                                <div class="card-text mb-2 mt-2">
                                    {{Str::limit(strip_tags($wallet->description->description ?? ''),300,' ...')}}
                                </div>
                                <p class="card-text">
                                    <small class="text-muted">بروزرسانی در {{jdate($wallet->updated_at)->ago()}}</small>
                                </p>
                            </div>
                        </div>
                    @endforeach

                </div>
                <div class="pagination-container mt-3">{{$wallets->withQueryString()->links()}}</div>
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

                    <div id="collapseOne" class="collapse @if(isset($filterData)) show @else hide @endif" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <form>
                                <input hidden name="w-filter" value="1">
                                <div id="widgets" class="d-flex flex-column">
                                    <div id="security-filter-container" class="mb-1">
                                        <select id="security-filter" class="wallet-filter-select my-1" name="security">
                                            @if(isset($filterData['security']))
                                                <option selected
                                                        value="{{$filterData['security']}}">{{$securities[$filterData['security']]}}</option>
                                            @else
                                                <option selected disabled></option>
                                            @endif
                                            @foreach($securities as $key => $security)
                                                <option value="{{$key}}">{{$security}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="tf-container" class="my-2">
                                        <label class="switch">
                                            <input name="trading_facility" type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                        قابلیت معامله
                                    </div>
                                    <div id="platforms-filter-container" class="my-1">
                                        <select id="platforms-filter" class="wallet-filter-select" name="platforms[]"
                                                multiple="multiple">
                                            @foreach($platforms as $key => $platform)
                                                <option
                                                    @isset($filterData['platforms'])
                                                    @foreach($filterData['platforms'] as $selectedPlatform)
                                                    @if($selectedPlatform === $key)
                                                    selected
                                                    @break
                                                    @endif
                                                    @endforeach
                                                    @endisset
                                                    value="{{$key}}">{{$platform}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="features-filter-container" class="my-1">
                                        <select id="features-filter" class="wallet-filter-select" name="features[]"
                                                multiple="multiple">
                                            @foreach($features as $key => $feature)
                                                <option
                                                    @isset($filterData['features'])
                                                    @foreach($filterData['features'] as $selectedFeature)
                                                    @if($selectedFeature === $key)
                                                    selected
                                                    @break
                                                    @endif
                                                    @endforeach
                                                    @endisset
                                                    value="{{$key}}">{{$feature}}</option>
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

