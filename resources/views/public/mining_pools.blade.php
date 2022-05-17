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
        $("#payments-filter").select2(

            {
                placeholder: 'نوع پرداخت',
                dir: 'rtl',
                language: 'fa',
                width: '100%',
            }
        );
        $("#features-filter").select2(

            {
                placeholder: 'نوع دستگاه',
                dir: 'rtl',
                language: 'fa',
                width: '100%',
            }
        );
    </script>
@endpush
@section('page_title','استخر های استخراج')
@section('body')
    <div class="row no-gutters">
        <div class="col-md-12">
            <h1 class="mt-5 text-center">فهرست تمامی استخر های استخراج </h1>
        </div>
    </div>
    <div class="row no-gutters">
        <div class="col-md-10 order-last pt-5 px-2 pl-md-3">
            @php
                $total_items = $mining_pools->count();
                $item_per_row =  4;
            @endphp
            @if($total_items > 0)
            <div class="card-columns masonry-container">
                @foreach($mining_pools as $key => $mining_pool)

                    <div class="card bg-transparent border-light">
                        <img width="250" height="250" class="d-block rounded-image mr-auto ml-auto img-fluid p-5"
                             src="{{asset(isset($mining_pool->image->path) ? $mining_pool->image->path : 'image/icon/mining_pool/large/default.png' ) }}"
                             alt="لوگو {{ $mining_pool->name_persian ?? $mining_pool->name}}">
                        <div class="card-body">
                            <h2 class="card-title"><a
                                        href="{{route('mining_pools.single',$mining_pool->slug)}}">{{$mining_pool->name_persian ?? $mining_pool->name}}</a>
                            </h2>
                            <div class="card-text d-inline-block coin-meta">
                            </div>
                            <div class="card-text mb-2 mt-2">
                                {{Str::limit(strip_tags($mining_pool->description->description ?? ''),300,' ...')}}
                            </div>
                            <p class="card-text">
                                <small class="text-muted">بروزرسانی در {{jdate($mining_pool->updated_at)->ago()}}</small>
                            </p>
                        </div>
                    </div>
                @endforeach

            </div>
            <div class="pagination-container mt-3">{{$mining_pools->withQueryString()->links()}}</div>
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
                                <input hidden name="mp-filter" value="1">
                                <div id="widgets" class="d-flex flex-column">


                                    <div id="merge-mining-container" class="my-2">
                                        <label class="switch half-switch">
                                            <input name="merge-mining" {{ isset($filterData['merge-mining']) ? 'checked' : '' }} type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                        Merge Mining
                                    </div>
                                    <div id="tx-fee-shared-container" class="my-2">
                                        <label class="switch half-switch">
                                            <input name="tx-fee-shared" {{ isset($filterData['tx-fee-shared']) ? 'checked' : '' }} type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                        تقسیم کارمزد تراکنش
                                    </div>
                                    <div id="features-filter-container" class="my-1">
                                        <select id="features-filter" class="wallet-filter-select" name="features[]"
                                                multiple="multiple">
                                            @foreach($filterSeed['pool_features'] as $key => $feature)
                                                <option
                                                    @isset($filterData['features'])
                                                    @foreach($filterData['features'] as $selectedFeature)
                                                    @if($selectedFeature === $feature)
                                                    selected
                                                    @break
                                                    @endif
                                                    @endforeach
                                                    @endisset
                                                    value="{{$feature}}">{{$feature}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="average-fee-range-container" class="my-1">
                                        <label class="w-100">
                                            متوسط کارمزد
                                            <div class='multi-range' data-lbound='{{ isset($filterData['average-fee-min']) ? round($filterData['average-fee-min']) : round($filterSeed['min_average_fee'])}}'
                                                 data-ubound='{{ isset($filterData['average-fee-max']) ? round($filterData['average-fee-max']) : round($filterSeed['max_average_fee'])}}'>
                                                <hr>

                                                <input name="average-fee-min" class="lrange" type='range'
                                                       min='{{round($filterSeed['min_average_fee'])}}' max='{{round($filterSeed['max_average_fee'])}}'
                                                       step='0.5' value='{{ isset($filterData['average-fee-min']) ? round($filterData['average-fee-min']) : round($filterSeed['min_average_fee'])}}'
                                                       oninput='this.parentNode.dataset.lbound=this.value;'
                                                />

                                                <input name="average-fee-max" class="hrange" type='range'
                                                       min='{{round($filterSeed['min_average_fee'])}}' max='{{round($filterSeed['max_average_fee'])}}'
                                                       step='0.5' value='{{ isset($filterData['average-fee-max']) ? round($filterData['average-fee-max']) : round($filterSeed['max_average_fee'])}}'
                                                       oninput='this.parentNode.dataset.ubound=this.value;'
                                                />


                                            </div>
                                        </label>
                                    </div>
                                    <div id="payment-filter-container" class="my-1">
                                        <select id="payments-filter" class="wallet-filter-select" name="payments[]"
                                                multiple="multiple">
                                            @foreach($filterSeed['payment_types'] as $key => $type)
                                                <option
                                                    @isset($filterData['payments'])
                                                    @foreach($filterData['payments'] as $selectedPayment)
                                                    @if($selectedPayment === $type)
                                                    selected
                                                    @break
                                                    @endif
                                                    @endforeach
                                                    @endisset
                                                    value="{{$type}}">{{$type}}</option>
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

