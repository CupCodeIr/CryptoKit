{{-- Created by PhpStorm.--}}
{{-- User: Artin--}}
{{-- Date: 6/30/2020--}}
{{-- Time: 6:50 PM--}}
@extends('public.master')
@php
    function number_clean($num){

      //remove zeros from end of number ie. 140.00000 becomes 140.
      $clean = rtrim($num, '0');
      //remove zeros from front of number ie. 0.33 becomes .33
      //$clean = ltrim($clean, '0');
      //remove decimal point if an integer ie. 140. becomes 140
      $clean = rtrim($clean, '.');

      return $clean;
    }
    $remove_empty_items = function($array){
        $cleaned_array = [];
    foreach ($array as $key => $item){
        if($item !== "")
            $cleaned_array[$key] = $item;
    }
            return $cleaned_array;

    };
$coin_extra = json_decode($coin->extra);

$coin_homepage = $remove_empty_items($coin_extra->links->homepage);
$coin_blockchain_site = $remove_empty_items($coin_extra->links->blockchain_site);
$coin_official_forum_url = $remove_empty_items($coin_extra->links->official_forum_url);
$coin_chat_url = $remove_empty_items($coin_extra->links->chat_url);
$coin_announcement_url = $remove_empty_items($coin_extra->links->announcement_url);
$coin_social = $remove_empty_items([
        'twitter_screen_name' => $coin_extra->links->twitter_screen_name,
        'facebook_username' => $coin_extra->links->facebook_username,
        'bitcointalk_thread_identifier' => $coin_extra->links->bitcointalk_thread_identifier,
        'telegram_channel_identifier' => $coin_extra->links->telegram_channel_identifier,
        'subreddit_url' => $coin_extra->links->subreddit_url,
]);
 $coin_repo_github = $remove_empty_items($coin_extra->links->repos_url->github);
    $coin_repo_bitbucket = $remove_empty_items($coin_extra->links->repos_url->bitbucket);
    $price = number_clean(number_format($coin->price * $full_selected_currency->usd_buy_price,$full_selected_currency->floating_point));
    $highest_price = number_clean(number_format($coin->ath * $full_selected_currency->usd_buy_price,$full_selected_currency->floating_point));
    $lowest_price = number_clean(number_format($coin->atl * $full_selected_currency->usd_buy_price,$full_selected_currency->floating_point));
    $highest_price_24 = number_clean(number_format($coin->high_24 * $full_selected_currency->usd_buy_price,$full_selected_currency->floating_point));
    $lowest_price_24 = number_clean(number_format($coin->low_24 * $full_selected_currency->usd_buy_price,$full_selected_currency->floating_point));
    $circulating_percentage = ($coin->total_supply === null) ? 0 : round(($coin->circulating / $coin->total_supply) * 100);
    $symbol_upper = Str::upper($coin->symbol);
    $signals_status = [

            'bearish' => [
                'name' => 'خرسی',
                'to' => '#992626',
                'value' => '0.33'

            ],
            'neutral' => [
                'name' => 'خنثی',
                'to' => '#FFEA82',
                'value' => '0.66'

            ],
            'bullish' => [
                'name' => 'گاوی',
                'to' => '#2fa839',
                'value' => '1.0'

            ]
];

@endphp
@push('home-page-styles')
    <link rel="stylesheet" href="{{asset('css/coin-mixed.css')}}">
@endpush
@push('home-page-scripts')
    <script type="text/javascript" src="{{asset('js/coin-mixed.js')}}"></script>
    <script>
        $(document).ready(function () {
                @isset($coin->trading_signal)
            let bar = new ProgressBar.SemiCircle("#in-out-var", {
                    strokeWidth: 6,
                    trailColor: '#eee',
                    trailWidth: 1,
                    easing: 'easeInOut',
                    duration: 1400,
                    svgStyle: null,
                    text: {
                        value: '',
                        alignToBottom: false,
                    },
                    // Set default step function for all animate calls
                    step: (state, bar,attachment) => {
                        bar.path.setAttribute('stroke', state.color);
                    }
                });
            bar.text.style.fontSize = '14px';
            bar.text.style.color = 'white';
            bar.animate({{$signals_status[$coin->trading_signal->inOutVar['sentiment']]['value']}},{
                from: { color: '#FFEA82'},
                to: { color: '{{$signals_status[$coin->trading_signal->inOutVar["sentiment"]]["to"]}}'}
            },function () {
                bar.setText('{{number_format($coin->trading_signal->inOutVar['value'] * 100,2) . "% (" . $signals_status[$coin->trading_signal->inOutVar['sentiment']]['name']}})');
            });  // Number from 0.0 to 1.0

            let bar2 = new ProgressBar.SemiCircle("#large-txs-var", {
                strokeWidth: 6,
                trailColor: '#eee',
                trailWidth: 1,
                easing: 'easeInOut',
                duration: 1400,
                svgStyle: null,
                text: {
                    value: '',
                    alignToBottom: false,
                },
                // Set default step function for all animate calls
                step: (state, bar,attachment) => {
                    bar.path.setAttribute('stroke', state.color);
                }
            });
            bar2.text.style.fontSize = '14px';
            bar2.text.style.color = 'white';
            bar2.animate({{$signals_status[$coin->trading_signal->largetxsVar['sentiment']]['value']}},{
                from: { color: '#FFEA82'},
                to: { color: '{{$signals_status[$coin->trading_signal->largetxsVar["sentiment"]]["to"]}}'}}, function () {

                bar2.setText('{{number_format($coin->trading_signal->largetxsVar['value'] * 100,2) . "% (" . $signals_status[$coin->trading_signal->largetxsVar['sentiment']]['name']}})');
            });  // Number from 0.0 to 1.0

            let bar3 = new ProgressBar.SemiCircle("#concentration-var", {
                strokeWidth: 6,
                trailColor: '#eee',
                trailWidth: 1,
                easing: 'easeInOut',
                duration: 1400,
                svgStyle: null,
                text: {
                    value: '',
                    alignToBottom: false,
                },
                // Set default step function for all animate calls
                step: (state, bar,attachment) => {
                    bar.path.setAttribute('stroke', state.color);
                }
            });
            bar3.text.style.fontSize = '14px';
            bar3.text.style.color = 'white';
            bar3.animate({{$signals_status[$coin->trading_signal->concentrationVar['sentiment']]['value']}},{
                from: { color: '#FFEA82'},
                to: { color: '{{$signals_status[$coin->trading_signal->concentrationVar["sentiment"]]["to"]}}'}}, function () {
                bar3.setText('{{number_format($coin->trading_signal->concentrationVar['value'] * 100,2) . "% (" . $signals_status[$coin->trading_signal->concentrationVar['sentiment']]['name']}})');
            });  // Number from 0.0 to 1.0

            let bar4 = new ProgressBar.SemiCircle("#addresses-net-growth", {
                strokeWidth: 6,
                trailColor: '#eee',
                trailWidth: 1,
                easing: 'easeInOut',
                duration: 1400,
                svgStyle: null,
                text: {
                    value: '',
                    alignToBottom: false,
                },
                // Set default step function for all animate calls
                step: (state, bar,attachment) => {
                    bar.path.setAttribute('stroke', state.color);
                }
            });
            bar4.text.style.fontSize = '14px';
            bar4.text.style.color = 'white';
            bar4.animate({{$signals_status[$coin->trading_signal->addressesNetGrowth['sentiment']]['value']}},{
                from: { color: '#FFEA82'},
                to: { color: '{{$signals_status[$coin->trading_signal->addressesNetGrowth["sentiment"]]["to"]}}'}}, function () {
                bar4.setText('{{number_format($coin->trading_signal->addressesNetGrowth['value'] * 100,2) . "% (" . $signals_status[$coin->trading_signal->addressesNetGrowth['sentiment']]['name']}})');
            });  // Number from 0.0 to 1.0
            @endisset

        });


    </script>
@endpush
@section('page_title',__($coin->name))
@section('body')
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-3 mb-1">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb border-light bg-transparent">
                        <li class="breadcrumb-item"><a href="{{route('coins.index')}}">کوین ها</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__($coin->name)}}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="jumbotron jumbotron-fluid rounded bg-transparent border-light">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <img
                            src="{{asset(isset($coin->image->path) ? $coin->image->path : 'image/icon/coin/large/coin-default.png' ) }}"
                            class="img-fluid d-block mr-auto ml-auto mr-sm-5" height="150" width="150"
                            alt="لوگو {{__($coin->name)}}">
                        <h1 class="mt-4 text-center text-sm-right">{{__($coin->name)}} ({{$symbol_upper}})</h1>
                        @isset($coin->country)
                            <small class="text-center">کشور
                                : {{$coin->country->name_persian ?? $coin->country->name}}</small>
                        @endisset
                        <div class="coin-description">
                            {{$coin->description->description ?? ''}}
                        </div>
                        <div class="coin-links mt-3">

                            {{-- Collapse Section buttons --}}

                            @empty(!$coin_homepage)
                                <button class="btn btn-primary m-1" type="button" data-toggle="collapse"
                                        data-target="#linkHomePageCollapse" aria-expanded="false"
                                        aria-controls="linkHomePageCollapse">
                                    صفحه اصلی
                                </button>
                            @endempty
                            @empty(!$coin_blockchain_site)
                                <button class="btn btn-primary m-1" type="button" data-toggle="collapse"
                                        data-target="#linkBlockchainSiteCollapse" aria-expanded="false"
                                        aria-controls="linkBlockchainSiteCollapse">
                                    بلاک چین
                                </button>
                            @endempty
                            @empty(!$coin_official_forum_url)
                                <button class="btn m-1 btn-primary" type="button" data-toggle="collapse"
                                        data-target="#linkOfficialForumCollapse" aria-expanded="false"
                                        aria-controls="linkOfficialForumCollapse">
                                    انجمن های گفتگو رسمی
                                </button>
                            @endempty
                            @empty(!$coin_chat_url)
                                <button class="btn m-1 btn-primary" type="button" data-toggle="collapse"
                                        data-target="#linkChatCollapse" aria-expanded="false"
                                        aria-controls="linkChatCollapse">
                                    چت
                                </button>
                            @endempty
                            @empty(!$coin_announcement_url)
                                <button class="btn m-1 btn-primary" type="button" data-toggle="collapse"
                                        data-target="#linkAnnouncementCollapse" aria-expanded="false"
                                        aria-controls="linkAnnouncementCollapse">
                                    اطلاعیه ها
                                </button>
                            @endempty
                            @empty(!$coin_social)
                                <button class="btn m-1 btn-primary" type="button" data-toggle="collapse"
                                        data-target="#linkSocialCollapse" aria-expanded="false"
                                        aria-controls="linkSocialCollapse">
                                    شبکه های اجتماعی
                                </button>
                            @endempty
                            @empty(!$coin_repo_github)
                                <button class="btn m-1 btn-primary" type="button" data-toggle="collapse"
                                        data-target="#linkGithubCollapse" aria-expanded="false"
                                        aria-controls="linkGithubCollapse">
                                    مخزن گیت هاب
                                </button>
                            @endempty
                            @empty(!$coin_repo_bitbucket)
                                <button class="btn m-1 btn-primary" type="button" data-toggle="collapse"
                                        data-target="#linkBitBucketCollapse" aria-expanded="false"
                                        aria-controls="linkBitBucketCollapse">
                                    مخزن بیت باکت
                                </button>
                            @endempty

                            {{-- Collapse Sections --}}
                            <div class="collapse-section text-left">
                                @empty(!$coin_homepage)
                                    <div class="collapse mt-3" id="linkHomePageCollapse">
                                        <div class="links-card bg-transparent border-light links-card-body">
                                            @foreach($coin_homepage as $link)

                                                <a rel="noreferrer noopener" target="_blank" href="{{$link}}">
                                                    {{parse_url($link)['host']}}
                                                </a>
                                                @if(!$loop->last)
                                                    /
                                                @endif

                                            @endforeach
                                        </div>
                                    </div>
                                @endempty
                                @empty(!$coin_blockchain_site)
                                    <div class="collapse mt-3" id="linkBlockchainSiteCollapse">
                                        <div class="links-card bg-transparent border-light links-card-body">
                                            @foreach($coin_blockchain_site as $link)

                                                <a rel="noreferrer noopener" target="_blank" href="{{$link}}">
                                                    {{parse_url($link)['host']}}
                                                </a>
                                                @if(!$loop->last)
                                                    /

                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endempty
                                @empty(!$coin_official_forum_url)
                                    <div class="collapse mt-3" id="linkOfficialForumCollapse">
                                        <div class="links-card bg-transparent border-light links-card-body">
                                            @foreach($coin_official_forum_url as $link)
                                                <a rel="noreferrer noopener" target="_blank" href="{{$link}}">
                                                    {{parse_url($link)['host']}}
                                                </a>
                                                @if(!$loop->last)
                                                    /
                                                @endif

                                            @endforeach
                                        </div>
                                    </div>
                                @endempty
                                @empty(!$coin_chat_url)
                                    <div class="collapse mt-3" id="linkChatCollapse">
                                        <div class="links-card bg-transparent border-light links-card-body">
                                            @foreach($coin_chat_url as $link)

                                                <a rel="noreferrer noopener" target="_blank" href="{{$link}}">
                                                    {{parse_url($link)['host']}}
                                                </a>
                                                @if(!$loop->last)
                                                    /
                                                @endif

                                            @endforeach
                                        </div>
                                    </div>
                                @endempty
                                @empty(!$coin_announcement_url)
                                    <div class="collapse mt-3" id="linkAnnouncementCollapse">
                                        <div class="links-card bg-transparent border-light links-card-body">
                                            @foreach($coin_announcement_url as $link)

                                                <a rel="noreferrer noopener" target="_blank" href="{{$link}}">
                                                    {{parse_url($link)['host']}}
                                                </a>
                                                @if(!$loop->last)
                                                    /
                                                @endif

                                            @endforeach
                                        </div>
                                    </div>
                                @endempty
                                @empty(!$coin_social)
                                    <div class="rtl text-right collapse mt-3" id="linkSocialCollapse">
                                        <div class="links-card bg-transparent border-light links-card-body d-flex">
                                            @isset($coin_social['twitter_screen_name'])
                                                @empty(!$coin_social['twitter_screen_name'])
                                                    <a class="mx-2" target="_blank" rel="noopener noreferrer"
                                                       href="https://twitter.com/{{$coin_social['twitter_screen_name']}}">
                                                        <div class="bg-twitter"></div>
                                                    </a>
                                                @endempty
                                            @endisset
                                            @isset($coin_social['facebook_username'])
                                                @empty(!$coin_social['facebook_username'])
                                                    <a class="mx-2" target="_blank" rel="noopener noreferrer"
                                                       href="https://facebook.com/{{$coin_social['facebook_username']}}">
                                                        <div class="bg-facebook"></div>
                                                    </a>
                                                @endempty
                                            @endisset
                                            @isset($coin_social['bitcointalk_thread_identifier'])
                                                @empty(!$coin_social['bitcointalk_thread_identifier'])
                                                    <a class="mx-2" target="_blank" rel="noopener noreferrer"
                                                       href="{{$coin_social['bitcointalk_thread_identifier']}}">
                                                        <div class="bg-bitcointalk"></div>
                                                    </a>
                                                @endempty
                                            @endisset
                                            @isset($coin_social['telegram_channel_identifier'])
                                                @empty(!$coin_social['telegram_channel_identifier'])
                                                    <a class="mx-2" target="_blank" rel="noopener noreferrer"
                                                       href="tg://resolve?domain={{$coin_social['telegram_channel_identifier']}}">
                                                        <div class="bg-telegram"></div>
                                                    </a>
                                                    <br>
                                                @endempty
                                            @endisset
                                            @isset($coin_social['subreddit_url'])
                                                @empty(!$coin_social['subreddit_url'])
                                                    <a class="mx-2" target="_blank" rel="noopener noreferrer"
                                                       href="{{$coin_social['subreddit_url']}}">
                                                        <div class="bg-reddit"></div>
                                                    </a>
                                                @endempty
                                            @endisset
                                        </div>
                                    </div>
                                @endempty
                                @empty(!$coin_repo_github)
                                    <div class="collapse mt-3" id="linkGithubCollapse">
                                        <div class="links-card bg-transparent border-light links-card-body">
                                            @foreach($coin_repo_github as $link)

                                                <a rel="noreferrer noopener" target="_blank" href="{{$link}}">
                                                    ({{parse_url($link)['path']}})
                                                </a>
                                                @if(!$loop->last)
                                                    |
                                                @endif

                                            @endforeach
                                        </div>
                                    </div>
                                @endempty
                                @empty(!$coin_repo_bitbucket)
                                    <div class="collapse mt-3" id="linkBitBucketCollapse">
                                        <div class="card bg-transparent border-light card-body">
                                            @foreach($coin_repo_bitbucket as $link)
                                                <a rel="noreferrer noopener" target="_blank" href="{{$link}}">
                                                    ({{parse_url($link)['path']}})
                                                </a>
                                                @if(!$loop->last)
                                                    |
                                                @endif

                                            @endforeach
                                        </div>
                                    </div>
                                @endempty
                            </div>


                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="text-center display-4">
                                    #{{$coin_rank}}
                                </div>
                            </div>
                            <div class="col-md-6">

                                <div class="coin-price text-left">
                                    {{$full_selected_currency->symbol . $price }}<sup
                                        class=" @if($coin->price_change_24 >= 0) {{"txt-green"}} @else {{"txt-red"}} @endif">{{number_format($coin->price_change_24,2)}}%</sup>
                                </div>
                            </div>
                        </div>

                        <hr data-content="اطلاعات بازار" class="hr-text">
                        <div class="coin-details-container mt-5">
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="">مارکت کپ</div>
                                <div
                                    class="">{{$full_selected_currency->symbol . number_format($coin->market_cap * $full_selected_currency->usd_buy_price)}}</div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="">تغییرات مارکت کپ 24 ساعت گذشته</div>
                                <div
                                    class="ltr @if($coin->market_cap_change_percentage_24h >= 0) {{"txt-green"}} @else {{"txt-red"}} @endif">{{number_format($coin->market_cap_change_percentage_24h,2)}}%
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="">حجم معاملات 24 ساعت گذشته</div>
                                <div
                                    class="">{{$full_selected_currency->symbol . number_format($coin->vol_24 * $full_selected_currency->usd_buy_price)}}</div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="">بیشترین قیمت 24 ساعت گذشته</div>
                                <div class="">{{$full_selected_currency->symbol . $highest_price_24 }}</div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="">کمترین قیمت 24 ساعت گذشته</div>
                                <div class="">{{$full_selected_currency->symbol . $lowest_price_24 }}</div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="">میزان در گردش</div>
                                <div class="">
                                    @if(isset($coin->total_supply))
                                        <div class="progress justify-content-end" id="coin-circulating-progress">
                                            <div data-toggle="tooltip" data-placement="top"
                                                 title="{{$circulating_percentage}}%"
                                                 class="progress-bar @if($circulating_percentage <= 25 ) bg-success @elseif($circulating_percentage <= 50) bg-info @elseif($circulating_percentage <= 75) bg-warning @else bg-danger @endif progress-bar-striped progress-bar-animated"
                                                 role="progressbar" aria-valuenow="{{$circulating_percentage}}"
                                                 aria-valuemin="0" aria-valuemax="100"
                                                 style="width: {{$circulating_percentage}}%">
                                                ({{number_format($coin->circulating)}}
                                                /{{number_format($coin->total_supply)}})
                                            </div>
                                        </div>
                                    @else
                                        <div class="ltr">
                                            {{$coin->circulating }}/<strong>&#8734;</strong>

                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class=>بیشترین قیمت</div>
                                <div class="">{{$full_selected_currency->symbol . $highest_price }} در
                                    تاریخ {{\Morilog\Jalali\Jalalian::forge($coin->ath_date)->format("%A, %d %B %y ساعت H:i:s")}} </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class=>کمترین قیمت</div>
                                <div class="">{{$full_selected_currency->symbol . $lowest_price }} در
                                    تاریخ {{\Morilog\Jalali\Jalalian::forge($coin->atl_date)->format("%A, %d %B %y ساعت H:i:s")}}</div>
                            </div>
                        </div>
                        <hr data-content="اطلاعات شبکه" class="hr-text mt-5">
                        <div class="coin-network_container mt-5">
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="">الگوریتم تولید هش</div>
                                <div
                                    class="">{{$coin->algorithm}}</div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="">الگوریتم اثبات</div>
                                <div
                                    class="">{{$coin->proof_type}}</div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="">زمان مورد نیاز ایجاد بلاک</div>
                                <div
                                    class="">{{$coin->block_time}} دقیقه
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="">پاداش استخراج</div>
                                <div
                                    class="">{{$coin->block_reward . $symbol_upper}}</div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="">قدرت تولید هش شبکه (برثانیه)</div>
                                <div
                                    class="">{{number_format($coin->hash_per_second)}}</div>
                            </div>
                            @isset($coin_extra->genesis_date)
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <div class="">تاریخ ایجاد اولین بلاک</div>
                                    <div
                                        class="">{{jdate($coin_extra->genesis_date)->format("%A, %d %B %y")}}</div>
                                </div>
                            @endisset
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="">برچسب ها</div>
                                <div>
                                    @foreach($coin_extra->categories as $category)
                                        {{$category}}@unless($loop->last),@endunless
                                    @endforeach
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
                    @isset($coin->trading_signal)
                        <div class="card mt-3 link-white bg-tertiary">
                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"
                                            aria-expanded="true" aria-controls="collapseOne">
                                        سیگنال های شبکه (On Chain)
                                    </button>
                                </h5>
                            </div>

                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                 data-parent="#accordion">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mt-3 mt-sm-0 ltr d-flex flex-column">
                                            <div class="signal-title d-flex align-self-center"><div>با پول</div><div data-toggle="tooltip" data-placement="bottom" title="تغییر در سیگنال مومنتوم آدرس های 'In the money'" class="bg-question ml-1 mb-3"></div></div>
                                            <div class="w-75 d-block mx-auto" id="in-out-var"></div>
                                        </div>
                                        <div class="col-md-3 mt-3 mt-sm-0 ltr d-flex flex-column">
                                            <div class="signal-title d-flex align-self-center"><div>تراکنش های بزرگ</div><div data-toggle="tooltip" data-placement="bottom" title="اندیکاتور مومنتوم تعداد تراکنش های بیشتر از 100 هزار دلار" class="bg-question ml-1 mb-3"></div></div>
                                            <div class="w-75 d-block mx-auto" id="large-txs-var"></div>
                                        </div>
                                        <div class="col-md-3 mt-3 mt-sm-0 ltr d-flex flex-column">
                                            <div class="signal-title d-flex align-self-center"><div>رشد خالص شبکه</div><div data-toggle="tooltip" data-placement="bottom" title="مومنتوم سیگنالی که نشان از رشد توکن های شبکه دارد (اختلاف آدرس های جدید به آدرس هایی که صفر می شوند)  " class="bg-question ml-1 mb-3"></div></div>
                                            <div class="w-75 d-block mx-auto" id="addresses-net-growth"></div>
                                        </div>
                                        <div class="col-md-3 mt-3 mt-sm-0 ltr d-flex flex-column">
                                            <div class="signal-title d-flex align-self-center"><div>جمع شدگی</div><div data-toggle="tooltip" data-placement="bottom" title="افزایش (گاوی) یا کاهش (خرسی) پوزیشن های سپرده گذاران بزرگ" class="bg-question ml-1 mb-3"></div></div>
                                            <div class="w-75 d-block mx-auto" id="concentration-var"></div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endisset
                    <div class="card mt-3 link-white bg-tertiary">
                        <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo"
                                        aria-expanded="false" aria-controls="collapseTwo">
                                    صرافی های {{__($coin->name)}}
                                </button>
                            </h5>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                            <div class="card-body">
                                <table class="link-black w-100 text-dark text-center responsive display" id="coin-exchanges-table">
                                    <thead>
                                    <tr>
                                        <th>ردیف</th>
                                        <th>نام</th>
                                        <th>متمرکز</th>
                                        <th>کشور</th>
                                        <th>رتبه</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($coin->visible_exchanges as $exchange)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td><a href="{{route('exchanges.single',$exchange->source_id)}}">{{$exchange->name_persian ?? $exchange->name }}</a></td>
                                            <td>{{($exchange->centralized) ? "بله" : "خیر"}}</td>
                                            <td>{{$exchange->country->name_persian ?? $exchange->country->name}}</td>
                                            <td>
                                                {{$exchange->trust_score_rank ?? "نامشخص"}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>

                            </div>
                        </div>
                    </div>
                    <div class="card mt-3 link-white bg-tertiary">
                        <div class="card-header" id="headingThree">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree"
                                        aria-expanded="false" aria-controls="collapseThree">
                                    کیف پول های {{__($coin->name)}}
                                </button>
                            </h5>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                            <div class="card-body">
                                <table class="link-black text-dark w-100 text-center responsive display" id="coin-wallets-table">
                                    <thead>
                                    <tr>
                                        <th>ردیف</th>
                                        <th>نام</th>
                                        <th>امنیت</th>
                                        <th>ناشناس</th>
                                        <th>استفاده آسان</th>
                                        <th>امکان معامله</th>
                                        <th>رتبه</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($coin->visible_wallets as $wallet)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td><a href="{{route('wallets.single',$wallet->source_id)}}">{{$wallet->name_persian ?? $wallet->name }}</a></td>
                                            <td>{{($wallet->security) ?? "نامشخص"}}</td>
                                            <td>{{($wallet->anonymity) ?? "نامشخص"}}</td>
                                            <td>{{$wallet->ease_of_use ?? "نامشخص"}}</td>
                                            <td>{{$wallet->has_trading_facilities ? "بله" : "خیر"}}</td>
                                            <td>
                                                {{$wallet->rank ?? "نامشخص"}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>

                            </div>
                        </div>
                    </div>
                    <div class="card mt-3 link-white bg-tertiary">
                        <div class="card-header" id="headingFour">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour"
                                        aria-expanded="false" aria-controls="collapseFour">
                                    استخر های استخراج {{__($coin->name)}}
                                </button>
                            </h5>
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                            <div class="card-body">
                                <table class="text-dark w-100 text-center responsive display" id="coin-pools-table">
                                    <thead>
                                    <tr>
                                        <th>ردیف</th>
                                        <th>نام</th>
                                        <th>امکان Merge Mining</th>
                                        <th>دخالت ماینر در کارمزد تراکنش</th>
                                        <th>متوسط کارمزد</th>
                                        <th>نوع پرداخت</th>
                                        <th>رتبه</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($coin->visible_miningPools as $mining_pool)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$mining_pool->name_persian ?? $mining_pool->name }}</td>
                                            <td>{{($mining_pool->can_merge_mining) ? "دارد" : "ندارد"}}</td>
                                            <td>{{($mining_pool->tx_fee_shared_with_miner) ? "بله" : "خیر"}}</td>
                                            <td>{{$mining_pool->average_fee ?? "نامشخص"}}</td>
                                            <td>{{implode(',',$mining_pool->payment_type)}}</td>
                                            <td>
                                                {{$mining_pool->rank ?? "نامشخص"}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center mt-5 mb-5">
                <h2>تبدیل ارز</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5 order-1 pr-5 pl-5 order-md-3 ltr">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span id="exchange-from-symbol" class="input-group-text">{{$symbol_upper}}</span>
                    </div>
                    <input id="exchange-from" type="text" maxlength="15" value="1" class="form-control"
                           aria-label="مقدار مورد نظر">
                </div>
            </div>
            <div class="col-md-2 order-2">
                <div data-direction="fromto" id="reverse-icon" class=" d-block mx-auto bg-exchange-switch img-fluid" width="32" height="32"></div>
            </div>
            <div class="col-md-5 order-3 pr-5 pl-5 order-md-1 ltr">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span id="exchange-to-symbol"
                              class="input-group-text">{{$full_selected_currency->symbol}}</span>
                    </div>
                    <input id="exchange-to"
                           data-to="{{number_clean($full_selected_currency->usd_buy_price * $coin->price)}}"
                           value="{{number_clean($full_selected_currency->usd_buy_price * $coin->price)}}" disabled
                           type="text" class="form-control">
                </div>
            </div>
        </div>
        @empty(!$posts)
            <div class="row my-3 no-gutters">
                <div class="col-md-12 text-center">
                    <h2>آخرین نوشته های وبلاگ</h2>
                </div>
            </div>
            <div class="row text-secondary no-gutters">
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
