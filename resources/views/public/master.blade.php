<!DOCTYPE html>
<html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @stack('home-page-styles')
    <title>@yield('page_title') - کریپتوکیت</title>
</head>
<body itemscope itemtype="http://schema.org/WebSite">
<link itemprop="url" href="{{route('homepage')}}"/>
<meta itemprop="name" content="کریپتوکیت">
<div class="p-4 d-flex flex-md-row flex-column text-center justify-content-around primary-bg">
    <div class="p-4 p-md-0">کل رمزارزها: {{number_format($total_cryptos)}}</div>
    <div class="p-4 p-md-0">بازارها: {{number_format($total_markets)}}</div>
    <div class="p-4 p-md-0">مارکت
        کپ: {{ number_format($market_cap * $full_selected_currency->usd_buy_price,2) . $full_selected_currency->symbol }}</div>
    <div class="p-4 p-md-0">حجم
        معاملات: {{number_format($trade_vol * $full_selected_currency->usd_buy_price,3) . $full_selected_currency->symbol}}</div>
    <div class="p-4 p-md-0">تسلط بیت کوین: {{"%" . number_format($btc_dominance,3)}}</div>
    <div class="p-4 p-md-0">نرخ دلار سامانه
        سنا: {{ number_format($currencies['IRT']->usd_buy_price,$currencies['IRT']->floating_point)}}</div>
</div>
<div class="container-fluid secondary-bg px-0">

    <nav id="top-navbar" class="navbar navbar-dark sticky-top navbar-expand-lg secondary-bg">
        <button class="navbar-toggler w-100" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01"
                aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <div class="bg-logo_64 p1 img-fluid"></div>
            <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                <li class="nav-item active">
                    <a class="nav-link" href="{{route('homepage')}}">صفحه اصلی <span
                            class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">آموزش ها</a>
                </li>
                <li class="toolsMenu nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="toolsMenuDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        ابزارها
                    </a>
                    <div id="home-top-navbar" class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <div class="row m-0">
                            <div class="col-md-4">
                                <div class="d-flex align-items-end m-3">
                                    <div class="bg-coin"></div>
                                    <a href="{{route('coins.index')}}" class="mr-3 dropdown-item">کوین ها</a>
                                </div>
                                <div class="d-flex align-items-end m-3">
                                    <div class="bg-wallet"></div>
                                    <a href="{{route('wallets.index')}}" class="mr-3 dropdown-item">کیف پول ها</a>
                                </div>
                                <div class="d-flex align-items-end m-3">
                                    <div class="bg-exchange"></div>
                                    <a href="{{route('exchanges.index')}}" class="mr-3 dropdown-item">صرافی ها</a>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-end m-3">
                                    <div class="bg-mcompany"></div>
                                    <a href="{{route('mining_companies.index')}}" class="mr-3 dropdown-item">کمپانی های
                                        ماینینگ</a>
                                </div>
                                <div class="d-flex align-items-end m-3">
                                    <div class="bg-mequipment"></div>
                                    <a href="{{route('mining_equipments.index')}}" class="mr-3 dropdown-item">تجهیزات
                                        ماینینگ</a>
                                </div>
                                <div class="d-flex align-items-end m-3">
                                    <div class="bg-mpool"></div>
                                    <a href="{{route('mining_pools.index')}}" class="mr-3 dropdown-item">استخر های
                                        استخراج</a>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-end m-3">
                                    <div class="bg-map"></div>
                                    <a href="{{route('crypto_map.index')}}" class="mr-3 dropdown-item">نقشه ارز های
                                        دیجیتال</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <div id="currency-dropdown" class="btn-group mx-3">
                <button id="dropdown-selected-currency" type="button" class="btn btn-secondary dropdown-toggle"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="bg-{{$full_selected_currency->code}}"></span>
                    <span class="currency-name mr-3">{{$full_selected_currency->name}}</span>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    @foreach($currencies as $currency)
                        <a href="{{route('setCurrency',$currency->code)}}" class="dropdown-item">
                            <div class="bg-{{$currency->code}}"></div>
                            <span class="currency-name mr-3">{{$currency->name}}</span></a>
                    @endforeach
                </div>
            </div>
            <form itemprop="potentialAction" itemscope itemtype="http://schema.org/SearchAction" class="form-inline navbar-search-form my-2 my-lg-0">
                <meta itemprop="target" content="{{route('navSearch')}}?term={query}"/>
                <select id="tns-result-container"></select>
                <button class="btn btn-outline-primary mr-sm-3 my-2 my-sm-0" id="top-nav-search-btn">
                    <svg class="bi bi-search" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"/>
                        <path fill-rule="evenodd"
                              d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
                    </svg>
                </button>
            </form>
        </div>
    </nav>
@yield('body')
    <div id="copyAlert" class="alert alert-success alert-dismissible fade hide"  role="alert">
        با موفقیت کپی شد!
        <button id="hideAlert" type="button" class="close" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<!-- Footer -->
    <footer>

        <div class="row no-gutters primary-bg">
            <div class="col-md-4 p-4">
                <div class="card primary-bg border-0">
                    <div class="card-title d-inline-flex align-items-center p-2">
                        <div class="mr-4 bg-logo_64"></div>
                        <div class="mr-3 h5">کریپتوکیت</div>
                    </div>
                    <div class="card-body">
                        کریپتو کیت یک ابزار دیگر برای گسترش دانش درباره رمزارز یا ارز های دیجیتال است و سعی شده در این وب سایت تمامی ابزاری که برای کار با این ارز ها نیاز دارید، فراهم شود. هدف اصلی این است که با برطرف کردن کاستی هایی که در این زمینه وجود دارد، یک تارنمای جامع را برای کاربران فراهم کنیم.
                    </div>
                </div>
            </div>
            <div class="col-md-4 p-4">
                <div class="card primary-bg border-0">
                    <div class="card-title d-inline-flex align-items-center p-2">
                        <div class="mr-4 bg-way"></div>
                        <div class="mr-3 h5">لینک های مفید</div>
                    </div>
                    <div class="card-body">
                        <ul>
                            <li><a href="">درباره کریپتوکیت</a></li>
                            <li><a href="">آموزش ها</a></li>
                            <li><a href="">خبرها</a></li>
                            <li><a href="">منابع</a></li>
                            <li><a href="">تماس با ما</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4 p-4">
                <div class="card primary-bg border-0">
                    <div class="card-title d-inline-flex align-items-center p-2">
                        <div class="mr-4 bg-coffee"></div>
                        <div class="mr-3 h5">قهوه مهمان شما؟</div>
                    </div>
                    <div class="card-body">
                        <div id="donate-text">
                            <p>                        اگر از خدماتی که کریپتوکیت ارائه می کند خوشتان آمده و می خواهید ما را در این راه پشتیبانی کنید، می توانید ما را یک قهوه مهمان کنید. با کلیک بر روی هر آیکون زیر، آدرس کیف پول کریپتوکیت کپی می شود.
                            </p>
                        </div>
                        <div id="donate-icons" class="d-flex">
                            <div data-address="13rT7ZvWAQdEHYXAJM6BaZTAbJ4jbEgQMw" class="bg-bitcoin_btc"></div>
                            <div data-address="0x813be070fbf963142a9c7d7cddc22f8b833aef3c" class="bg-ethereum_eth"></div>
                            <div data-address="0x813be070fbf963142a9c7d7cddc22f8b833aef3c" class="bg-tether_usdt"></div>
                            <div data-address="13rT7ZvWAQdEHYXAJM6BaZTAbJ4jbEgQMw" class="bg-bitcoin_cash_bch"></div>
                            <div data-address="DdzFFzCqrhsuoHtC9fJRj2drbNtiE3R78JNSEt64iKGyNN1QqpgzNicjW9hvv64qWnHqDoC7tHWtb4gFktfiiicchErYNrDEH7h38uu4" class="bg-cardano_ada"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer -->


</div>
@stack('home-page-scripts')
<script>
    let searchBTN = '<svg class="bi bi-search" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"/><path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/></svg>';
    let closeBTN = '<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z"/><path fill-rule="evenodd" d="M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z"/></svg>';
    let searchPanelOpen = false;
    let select2 = $('#tns-result-container').select2({
        templateResult: select2IconEmbed,
        placeholder: 'کوین ها، صرافی ها و...',
        dir: 'rtl',
        maximumInputLength: 60,
        minimumInputLength: 3,
        language: 'fa',
        width: '250px',
        ajax: {
            url: '{{route('navSearch')}}',
            dataType: 'json',
            delay: 500
            // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
        }
    });

    function select2IconEmbed(state) {
        if (!state.id) {
            return state.text;
        }
        let baseUrl = "{{url('')}}";
        if (state.image !== null) {
            if (typeof state.symbol !== 'undefined')
                return $(
                    '<span><img alt="لوگو کوین' + state.text + '" width="24" height="24" src="' + baseUrl + '/' + state.image.path + '" class="img-flag" /> ' + state.text + '<span class="text-uppercase"> (' + state.symbol + ')</span></span>'
                );
            return $(
                '<span><img alt="لوگو' + state.text + '" width="24" height="24" src="' + baseUrl + '/' + state.image.path + '" class="img-flag" /> ' + state.text + '</span>'
            );

        } else return state;
    }

    $('#tns-result-container').on('select2:select', function (e) {

        let data = e.params.data;
        location.href = data.slug;
    });

    $('#tns-result-container').on('select2:closing', function (e) {

        let searchBTNDOM = $("#top-nav-search-btn");
        $('.select2').removeClass('select2-display');
        searchBTNDOM.empty();
        searchBTNDOM.append(searchBTN);
        searchPanelOpen = false;

    });

    $("#top-nav-search-btn").on("click", function (event) {
        event.preventDefault();
        search_btn_changer();
    });

    function search_btn_changer() {
        let searchBTNDOM = $("#top-nav-search-btn");
        if (searchPanelOpen) {
            $('.select2').removeClass('select2-display');
            select2.select2("close");
            searchBTNDOM.empty();
            searchBTNDOM.append(searchBTN);
            searchPanelOpen = false;
        } else {
            $('.select2').addClass('select2-display');
            select2.select2("open");
            searchBTNDOM.empty();
            searchBTNDOM.append(closeBTN);
            searchPanelOpen = true
        }
    }
    $(document).ready(function (){
        copyToClipboard();
    });
    $("#hideAlert").on('click',function (){
        $("#copyAlert").removeClass('show');

    });
    function copyToClipboard() {
        let donateIcons = document.getElementById("donate-icons").children;

        for(let i=0; i< donateIcons.length; i++){
            donateIcons[i].onclick = function(){
                let a = this.getAttribute('data-address');
                let textArea = document.createElement("textarea");
                textArea.value = a;
                document.body.appendChild(textArea);
                textArea.select();
                try {
                     let success = document.execCommand('copy');
                     if(success)          $('#copyAlert').addClass('show') ;


                } catch (err) {
                    console.log('Oops, unable to copy');
                }
                document.body.removeChild(textArea);
            }

        }
    }
</script>
</body>
