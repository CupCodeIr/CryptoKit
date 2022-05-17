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
@endpush
@section('page_title','کمپانی های ماینینگ')
@section('body')
    <div class="row no-gutters">
        <div class="col-md-12">
            <h1 class="mt-5 text-center">فهرست تمامی کمپانی های ماینینگ </h1>
        </div>
    </div>
    <div class="row no-gutters">
        <div class="col-md-12 p-5">
            @php
                $total_items = $mining_companies->count();
                $item_per_row =  4;
            @endphp
            <div class="card-columns masonry-container">
                @foreach($mining_companies as $key => $mining_company)

                    <div class="card bg-transparent border-light">
                        <img width="250" height="250" class="d-block mr-auto ml-auto img-fluid p-5"
                             src="{{asset(isset($mining_company->image->path) ? $mining_company->image->path : 'image/icon/mining_company/large/default.png' ) }}"
                             alt="لوگو {{ $mining_company->name_persian ?? $mining_company->name}}">
                        <div class="card-body">
                            <h2 class="card-title"><a
                                        href="{{route('mining_companies.single',$mining_company->slug)}}">{{$mining_company->name_persian ?? $mining_company->name}}</a>
                            </h2>
                            <div class="card-text d-inline-block coin-meta">
                            </div>
                            <div class="card-text mb-2 mt-2">
                                {{Str::limit(strip_tags($mining_company->description->description ?? ''),300,' ...')}}
                            </div>
                            <p class="card-text">
                                <small class="text-muted">بروزرسانی در {{jdate($mining_company->updated_at)->ago()}}</small>
                            </p>
                        </div>
                    </div>
                @endforeach

            </div>
            <div class="pagination-container mt-3">{{$mining_companies->links()}}</div>
        </div>
    </div>

@endsection

