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
        $("#algorithm-filter").select2(

            {
                placeholder: 'الگوریتم ها',
                dir: 'rtl',
                language: 'fa',
                width: '100%',
            }
        );
        $("#types-filter").select2(

            {
                placeholder: 'نوع دستگاه',
                dir: 'rtl',
                language: 'fa',
                width: '100%',
            }
        );
    </script>
@endpush
@section('page_title','تجهیزات ماینینگ')
@section('body')
    <div class="row no-gutters">
        <div class="col-md-12">
            <h1 class="mt-5 text-center">فهرست تمامی تجهیزات ماینینگ </h1>
        </div>
    </div>
    <div class="row no-gutters">
        <div class="col-md-10 order-last pt-5 px-2 pl-md-3">
            @php
                $total_items = $mining_equipments->count();
                $item_per_row =  4;
            @endphp
            @if($total_items > 0)
            <div class="card-columns masonry-container">
                @foreach($mining_equipments as $key => $mining_equipment)

                    <div class="card bg-transparent border-light">
                        <img width="250" height="250" class="d-block mr-auto ml-auto img-fluid p-5"
                             src="{{asset(isset($mining_equipment->image->path) ? $mining_equipment->image->path : 'image/icon/mining_equipment/large/default.png' ) }}"
                             alt="لوگو {{ $mining_equipment->name_persian ?? $mining_equipment->name}}">
                        <div class="card-body">
                            <h2 class="card-title"><a
                                    href="{{route('mining_equipments.single',$mining_equipment->slug)}}">{{$mining_equipment->name_persian ?? $mining_equipment->name}}</a>
                            </h2>
                            <div class="card-text d-inline-block coin-meta">
                            </div>
                            <div class="card-text mb-2 mt-2">
                                {{Str::limit(strip_tags($mining_equipment->description->description ?? ''),300,' ...')}}
                            </div>
                            <p class="card-text">
                                <small class="text-muted">بروزرسانی
                                    در {{jdate($mining_equipment->updated_at)->ago()}}</small>
                            </p>
                        </div>
                    </div>
                @endforeach

            </div>
            <div class="pagination-container mt-3">{{$mining_equipments->withQueryString()->links()}}</div>
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
                                <input hidden name="me-filter" value="1">
                                <div id="widgets" class="d-flex flex-column">
                                    <div id="algorithm-filter-container" class="my-1">
                                        <select id="algorithm-filter" class="mq-filter-select" name="algorithms[]"
                                                multiple="multiple">
                                            @foreach($filterSeed['algorithms'] as  $algorithm)
                                                <option
                                                    @isset($filterData['algorithms'])
                                                    @foreach($filterData['algorithms'] as $selectedAlgorithm)
                                                    @if($selectedAlgorithm === $algorithm)
                                                    selected
                                                    @break
                                                    @endif
                                                    @endforeach
                                                    @endisset
                                                    value="{{$algorithm}}">{{$algorithm}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="cost-range-container" class="my-1">
                                        <label class="w-100">
                                            قیمت
                                            <div class='multi-range' data-lbound='{{$filterData['cost-min'] ?? $filterSeed['min_price']}}'
                                                 data-ubound='{{$filterData['cost-max'] ?? $filterSeed['max_price']}}'>
                                                <hr>

                                                <input name="cost-min" class="lrange" type='range'
                                                       min='{{$filterSeed['min_price']}}'
                                                       max='{{$filterSeed['max_price']}}'
                                                       step='{{$filterSeed['max_price'] % 10}}'
                                                       value='{{$filterData['cost-min'] ?? $filterSeed['min_price']}}'
                                                       oninput='this.parentNode.dataset.lbound=this.value;'
                                                />

                                                <input name="cost-max" class="hrange" type='range'
                                                       min='{{$filterSeed['min_price']}}'
                                                       max='{{$filterSeed['max_price']}}'
                                                       step='{{$filterSeed['max_price'] % 10}}'
                                                       value='{{$filterData['cost-max'] ?? $filterSeed['max_price']}}'
                                                       oninput='this.parentNode.dataset.ubound=this.value;'
                                                />


                                            </div>
                                        </label>
                                    </div>
                                    <div id="types-filter-container" class="my-1">
                                        <select id="types-filter" class="mq-filter-select" name="types[]"
                                                multiple="multiple">
                                            @foreach($filterSeed['types'] as $type)
                                                <option
                                                    @isset($filterData['types'])
                                                    @foreach($filterData['types'] as $selectedType)
                                                    @if($selectedType === $type)
                                                    selected
                                                    @break
                                                    @endif
                                                    @endforeach
                                                    @endisset
                                                    value="{{$type}}">{{$type}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="hash-range-container" class="my-1">
                                        <label class="w-100">
                                            قدر تولید هش
                                            <div class='multi-range' data-lbound='{{$filterData['hash-min'] ?? $filterSeed['min_hashes']}}'
                                                 data-ubound='{{ $filterData['hash-max'] ?? $filterSeed['max_hashes']}}'>
                                                <hr>

                                                <input name="hash-min" class="lrange" type='range'
                                                       min='{{$filterSeed['min_hashes']}}'
                                                       max='{{$filterSeed['max_hashes']}}' step='2'
                                                       value='{{$filterData['min-hashes'] ?? $filterSeed['min_hashes']}}'
                                                       oninput='this.parentNode.dataset.lbound=this.value;'
                                                />

                                                <input name="hash-max" class="hrange" type='range'
                                                       min='{{$filterSeed['min_hashes']}}'
                                                       max='{{$filterSeed['max_hashes']}}' step='2'
                                                       value='{{ $filterData['hash-max'] ?? $filterSeed['max_hashes']}}'
                                                       oninput='this.parentNode.dataset.ubound=this.value;'
                                                />


                                            </div>
                                        </label>
                                    </div>
                                    <div id="pc-range-container" class="my-1">
                                        <label class="w-100">
                                            مصرف
                                            <div class='multi-range' data-lbound='{{$filterData['pc-min'] ?? $filterSeed['min_pc']}}'
                                                 data-ubound='{{$filterData['pc-max'] ?? $filterSeed['max_pc']}}'>
                                                <hr>

                                                <input name="pc-min" class="lrange" type='range'
                                                       min='{{$filterSeed['min_pc']}}' max='{{$filterSeed['max_pc']}}'
                                                       step='10' value='{{$filterData['pc-min'] ?? $filterSeed['min_pc']}}'
                                                       oninput='this.parentNode.dataset.lbound=this.value;'
                                                />

                                                <input name="pc-max" class="hrange" type='range'
                                                       min='{{$filterSeed['min_pc']}}' max='{{$filterSeed['max_pc']}}'
                                                       step='2' value='{{ $filterData['pc-max'] ?? $filterSeed['max_pc']}}'
                                                       oninput='this.parentNode.dataset.ubound=this.value;'
                                                />


                                            </div>
                                        </label>
                                    </div>
                                    <div  style="margin-top: 1.5rem" class="mb-1">
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

