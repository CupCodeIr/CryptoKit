@extends('public.master')
@push('home-page-styles')
    <link rel="stylesheet" href="{{asset('css/home.css')}}">
@endpush
@push('home-page-scripts')

    <script type="text/javascript" src="{{asset('js/home.js')}}"></script>
    <script>
        $(document).ready(function () {
            let table = $('#coins-summary-table').DataTable(
                {
                    "bInfo": false,
                    "bPaginate": false,
                    colReorder: true,
                    dom: 'Bfrtip',
                    buttons: [
                        {extend: 'copyHtml5', className: 'datatable-copy-btn'}

                    ],
                    fixedHeader: {
                        headerOffset: 50
                    },
                    responsive: {
                        details: {
                            display: $.fn.dataTable.Responsive.display.modal( {

                            } ),
                            renderer: $.fn.dataTable.Responsive.renderer.tableAll()
                        }
                    },
                    "language": {
                        "sEmptyTable": "هیچ داده‌ای در جدول وجود ندارد",
                        "sInfo": "نمایش _START_ تا _END_ از _TOTAL_ ردیف",
                        "sInfoEmpty": "نمایش 0 تا 0 از 0 ردیف",
                        "sInfoFiltered": "(فیلتر شده از _MAX_ ردیف)",
                        "sInfoPostFix": "",
                        "sInfoThousands": ",",
                        "sLengthMenu": "نمایش _MENU_ ردیف",
                        "sLoadingRecords": "در حال بارگزاری...",
                        "sProcessing": "در حال پردازش...",
                        "sSearch": "",
                        "searchPlaceholder": "جستجو کنید...",
                        "sZeroRecords": "رکوردی با این مشخصات پیدا نشد",
                        "oPaginate": {
                            "sFirst": "برگه‌ی نخست",
                            "sLast": "برگه‌ی آخر",
                            "sNext": "بعدی",
                            "sPrevious": "قبلی"
                        },
                        "oAria": {
                            "sSortAscending": ": فعال سازی نمایش به صورت صعودی",
                            "sSortDescending": ": فعال سازی نمایش به صورت نزولی"
                        },
                        buttons: {
                            copy: 'کپی',
                            copyTitle: '<div class="text-black-50">ذخیره سازی اطلاعات</div>',
                            copySuccess: {
                                _: '<div class="text-black-50">%d خط کپی شد</div>',
                                1: '<div class="text-black-50">1 خط کپی شد</div>'
                            }
                        }

                    }
                }
            );
            $('#coins-summary-table').on('click', '.dtr-control', function () {
                let elem = ($(this).parent()).children().last();
                let base = $(".dtr-details td").last();
                base.sparkline(elem.data("sparkline"), {
                    type: 'line',
                    fillColor: false,
                    lineColor: elem.data("color"),
                    width: '100px',
                    height: '40px',
                    spotColor: false,
                    tooltipFormat: false,
                    minSpotColor: false,
                    maxSpotColor: false,
                    highlightSpotColor: false,
                    highlightLineColor: false
                });

            });
            $('.dataTables_filter input').addClass('datatable-search-input');
            if( window.innerWidth > 893){
                $("*[data-sparkline]").each(function () {
                    $(this).sparkline($(this).data("sparkline"), {
                        type: 'line',
                        fillColor: false,
                        lineColor: $(this).data("color"),
                        width: '100px',
                        height: '40px',
                        spotColor: false,
                        tooltipFormat: false,
                        minSpotColor: false,
                        maxSpotColor: false,
                        highlightSpotColor: false,
                        highlightLineColor: false
                    });
                });
            }

        });
    </script>
@endpush
@section('page_title','صفحه اصلی')
@php
    function number_clean($num){
    if(strpos($num,'.') !== false)
        for($i=strlen($num) -1 ; $i>-1; $i--){
            if($num[$i] == '0' && $num[$i-1] == '0'){
                $num = substr($num, 0, -1);
            }
            else break;
        }
    return $num;
}

@endphp
@section('body')
    <div class="row no-gutters">
        <div class="col-md-12">
            <div class="">
                {{--<div id="effective-name">کریپتوکیت</div>--}}
                <svg class="img-fluid" id="eff">
                    <symbol id="s-text">
                        <text text-anchor="middle" x="50%" y="80%">CryptoKit</text>
                    </symbol>

                    <g class="g-ants">
                        <use xlink:href="#s-text" class="text-copy"></use>
                        <use xlink:href="#s-text" class="text-copy"></use>
                        <use xlink:href="#s-text" class="text-copy"></use>
                        <use xlink:href="#s-text" class="text-copy"></use>
                        <use xlink:href="#s-text" class="text-copy"></use>
                    </g>
                </svg>
            </div>
        </div>
    </div>
    <div class="row no-gutters">
        <div class="col-md-12 p-5">
            <table itemprop="mainEntity" itemscope itemtype="http://schema.org/ItemList" class="text-center responsive display"  id="coins-summary-table">
                <caption itemprop="name">قیمت ارز های دیجیتال</caption>
                <thead>
                <tr>
                    <th>ردیف</th>
                    <th>نام</th>
                    <th>مارکت کپ</th>
                    <th>قیمت</th>
                    <th>تغییرات (24 ساعت)</th>
                    <th>حجم معاملات (24 ساعت)</th>
                    <th>در چرخش</th>
                    <th>نمودار قیمت (7 ساعت)</th>
                </tr>
                </thead>
                <tbody>
                @foreach($coins as $key => $coin)
                    @php
                        $price = number_clean(number_format($coin->price * $full_selected_currency->usd_buy_price,$full_selected_currency->floating_point));
                    @endphp
                    <tr itemprop="itemListElement" itemscope itemtype="http://schema.org/ExchangeRateSpecification">
                        <td>{{$key + 1}}</td>
                        <td class="coin-id">
                            <div class="bg-bitcoin_btc bg-{{str_replace('-','_',$coin->slug)}}"></div>
                            <div itemprop="currency" content="{{$coin->symbol}}" class="mr-2">{{__($coin->name)}}</div>
                        </td>
                        <td>{{$full_selected_currency->symbol . number_format($coin->market_cap * $full_selected_currency->usd_buy_price)}}</td>
                        <td itemprop="currentExchangeRate" itemscope itemtype="http://schema.org/UnitPriceSpecification" ><span itemprop="priceCurrency" content="{{$full_selected_currency->code}}">{{$full_selected_currency->symbol}}</span><span itemprop="price">{{$price}}</span></td>
                        <td @if($coin->price_change_24 < 0) class="ltr text-danger"
                            @else style="color: green;" @endif>{{number_format($coin->price_change_24,2)}}%
                        </td>
                        <td>{{$full_selected_currency->symbol . number_format($coin->vol_24 * $full_selected_currency->usd_buy_price)}}</td>
                        <td class="ltr">{{number_format($coin->circulating) . " " . strtoupper($coin->symbol)}}</td>
                        <td data-color="@if($coin->price_change_24 < 0) #f78a8a
                            @else #4cb04c @endif" data-sparkline="{{ $coin->{'7d_sparkline'} }}"></td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            <a href="{{route('coins.index')}}" class="mt-3 d-flex justify-content-center btn btn-outline-light">مشاهده
                تمامی کوین ها</a>
        </div>

    </div>
    @empty(!$posts)
        <div class="row no-gutters my-3">
            <div class="col-md-12 text-center">
                <h2>آخرین نوشته های وبلاگ</h2>
            </div>
        </div>
        <div class="row text-secondary no-gutters ">
            @foreach($posts as $post)
                <div class="col-md-4 px-3 py-2">
                    <article class="card h-100">
                        <img class="card-img-top" src="{{$post->image['url'] ?? ''}}" alt="{{$post->post_title}}">
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


@endsection
