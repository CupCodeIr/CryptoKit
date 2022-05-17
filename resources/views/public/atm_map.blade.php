{{-- Created by PhpStorm.--}}
{{-- User: Artin--}}
{{-- Date: 6/30/2020--}}
{{-- Time: 6:50 PM--}}
@extends('public.master')
@push('home-page-styles')
    <link rel="stylesheet" href="{{asset('css/atm_map.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@push('home-page-scripts')
    <script type="text/javascript" src="{{asset('js/atm_map.js')}}"></script>
    <script src="https://api-maps.yandex.ru/2.1/?lang=en_RU&amp;apikey=cfd9e4bb-3f76-4b45-8b52-a99d2e7cb7bb"
            type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            let categories = {
                'default': 'bg-default',
                'shopping': 'bg-shopping',
                'atm': 'bg-atm',
                'attraction': 'bg-attraction',
                'cafe': 'bg-cafe',
                'transport': 'bg-transportation',
                'nightlife': 'bg-nightlife',
                'grocery': 'bg-grocery',
                'food': 'bg-food',
                'lodging': 'bg-lodging',
                'sports': 'bg-sport',
                'trezor retailer': 'bg-default',
                'drug store': 'bg-drug_store',
                'Travel Agency': 'bg-travel_agency',
                'Educational Business': 'bg-education',

            };
            let myMap;
            let currentUrl = '{{url()->current()}}';
            let hasStart = {{ ($coordinates) ? 'true' : 'false'  }}
                // Waiting for the API to load and DOM to be ready.
                ymaps.ready(init);

            function init() {
                var geolocation = ymaps.geolocation,
                    myMap = new ymaps.Map('map', {
                        center: [{{$coordinates ?? '35.696733, 51.209732'}}],
                        zoom: 16,
                        minZoom: 3,
                        suppressMapOpenBlock: true
                    }, {
                        searchControlProvider: 'yandex#search',
                    });
                myMap.options.set('scrollZoomSpeed', 0.5);
                if (!hasStart) {
                    /**
                     * Comparing the position calculated from the user's IP address
                     * and the position detected using the browser.
                     */
                    geolocation.get({
                        provider: 'yandex',
                        mapStateAutoApply: true
                    }).then(function (result) {
                        result.geoObjects.options.set({
                                iconLayout: 'default#image',
                                iconImageClipRect: [[10, 1090], [42, 1122]],
                                iconImageHref: '../image/crypto_atm.png',
                                iconImageSize: [32, 32]
                            }
                        );
                        result.geoObjects.get(0).properties.set({
                            hintContent: '<div class="text-right" style="direction: rtl">شما اینجایید!</div>'
                        });
                        myMap.geoObjects.add(result.geoObjects);
                    });

                    geolocation.get({
                        provider: 'browser',
                        mapStateAutoApply: true
                    }).then(function (result) {
                        /**
                         * We'll mark the position obtained through the browser in blue.
                         * If the browser does not support this functionality, the placemark will not be added to the map.
                         */
                        result.geoObjects.options.set({
                            iconLayout: 'default#image',
                            iconImageClipRect: [[10, 1090], [42, 1122]],
                            iconImageHref: '../image/crypto_atm.png',
                            iconImageSize: [32, 32]
                        });
                        result.geoObjects.get(0).properties.set({
                            hintContent: '<div class="text-right" style="direction: rtl">براساس موقعیت مرورگرتان، شما اینجایید!</div>'
                        });
                        myMap.geoObjects.add(result.geoObjects);
                    });
                }
                let loadingObjectManager = new ymaps.LoadingObjectManager('{{route('crypto_map.places')}}/?coordinates=%b',
                    {
                        // Enabling clusterization.
                        clusterize: true,
                        // Cluster options are set with the 'cluster' prefix.
                        clusterHasBalloon: false,
                        // Object options are set with the geoObject prefix.
                        geoObjectOpenBalloonOnClick: true,
                    });
                myMap.geoObjects.add(loadingObjectManager);
                myMap.geoObjects.options.set({
                    iconLayout: 'default#image',
                    iconImageClipRect: [[10, 230], [34, 254]],
                    iconImageHref: '../image/crypto_atm.png',
                    iconImageSize: [24, 24]
                });
                let selectedCoords;
                let toolsControl = new ymaps.control.ListBox({
                    data: {
                        content: 'ابزارها',
                        title: 'به اشتراک گذاری موقعیت انتخاب شده',
                        image: '{{asset('image/pin.png')}}',
                    },
                    items: [
                        new ymaps.control.ListBoxItem(
                            {
                                data: {
                                    content: 'مسیریابی با ویز',
                                    id: 0
                                }
                            }
                        ),
                        new ymaps.control.ListBoxItem(
                            {
                                data: {
                                    content: 'مسیریابی با گوگل مپ',
                                    id: 1
                                }
                            }
                        ),
                        new ymaps.control.ListBoxItem({options: {type: 'separator'}}),
                        new ymaps.control.ListBoxItem(
                            {
                                data: {
                                    content: 'ارسال به تلگرام',
                                    id: 2
                                }
                            }
                        ),
                        new ymaps.control.ListBoxItem(
                            {
                                data: {
                                    content: 'ارسال به واتس اپ ',
                                    id: 3
                                }
                            }
                        ),
                    ],
                    options: {
                        float: 'right',
                        size: 'large',
                        maxWidth: [120],
                        popupFloat: 'left',
                    }
                });
                let helpControl = new ymaps.control.Button({
                    data:{
                        content:'❔راهنمایی',

                    },
                    options:{
                        float:'right',
                    }
                });
                myMap.controls.add(helpControl);
                helpControl.events.add('click',function (){

                    $('#help-modal').modal();

                });
                function hasBalloonData(objectId) {
                    return loadingObjectManager.objects.getById(objectId).properties.balloonContent;
                }

                loadingObjectManager.objects.events.add('click', function (e) {
                    selectedCoords = e.get('coords');
                    let objectId = e.get('objectId');
                    let obj = loadingObjectManager.objects.getById(objectId);
                    if (hasBalloonData(objectId)) {
                        loadingObjectManager.objects.balloon.open(objectId);
                    } else {
                        obj.properties.balloonContent = "<div class=\"mt-3 lds-ripple\"><div>";
                        $.getJSON(
                            currentUrl + "/place/" + objectId, function (data) {
                                let wipedData = [];
                                for (const [key, value] of Object.entries(data.data)) {
                                    wipedData[key] = (value != null) ? value : ""
                                }
                                let content = "                <div class=\"atm_item_content_container\">" +
                                    "                           <div class=\"d-flex justify-content-between align-items-center mt-2\">" +
                                    "                                <div class=\"\">توضیحات:</div>" +
                                    "                                <div class=\"\">" + wipedData.description + "</div>" +
                                    "                                </div>" +
                                    "                            <div class=\"d-flex justify-content-between align-items-center mt-2\">" +
                                    "                                <div class=\"\">ساعت کاری:</div>" +
                                    "                                <div class=\"\">" + wipedData.opening_hours + "</div>" +
                                    "                                </div>" +
                                    "                            <div class=\"d-flex justify-content-between align-items-center mt-2\">" +
                                    "                                <div class=\"\">نشانی:</div>" +
                                    "                                <div class=\"\"></div>" + wipedData.state + " " + wipedData.city + " " + wipedData.state + " " + wipedData.street + " ساختمان شماره " + wipedData.place_number + " کدپستی " + wipedData.postcode + "</div><div class=\"d-flex mt-2\">";
                                if (wipedData.email !== "")
                                    content += "                <a class=\"mr-1\" href=\"mailto:" + wipedData.email + "\"> <div class=\"bg-email\"></div></a>";
                                if (wipedData.website !== "")
                                    content += "                <a class=\"mr-1\" target=\"_blank\" rel=\"noopener noreferer nofollow\" href=\"" + wipedData.website + "\"> <div class=\"bg-website\"></div></a>";
                                if (wipedData.phone !== "")
                                    content += "                <a class=\"mr-1\" href=\"tel:" + wipedData.phone + "\"> <div class=\"bg-phone\"></div></a>";
                                if (wipedData.fax !== "")
                                    content += "                <a class=\"mr-1\" href=\"fax:" + wipedData.fax + "\"> <div class=\"bg-fax\"></div></a>";
                                if (wipedData.facebook !== "")
                                    content += "                <a class=\"mr-1\" target=\"_blank\" href=\"https:facebook.com/" + wipedData.facebook + "\"> <div class=\"bg-facebook\"></div></a>";
                                if (wipedData.twitter !== "")
                                    content += "                <a class=\"mr-1\" target=\"_blank\" href=\"https:twitter.com/" + wipedData.twitter + "\"> <div class=\"bg-twitter\"></div></a>";
                                content += "</div></div>";
                                obj.properties.balloonContentHeader = "<div class='d-flex flex-row-reverse align-items-center'><div class='" + categories[loadingObjectManager.objects.getById(e.get('objectId')).cat] + "'></div><div class='map_item_header mr-1'>" + wipedData.name + "</div></div>";
                                obj.properties.balloonContent = content;

                                loadingObjectManager.objects.balloon.open(objectId);
                            }
                        );

                    }
                    myMap.controls.add(toolsControl, {floatIndex: 0});

                });
                toolsControl.events.add('click', function (e) {
                    /**
                     * Getting a reference to the clicked object.
                     * List item events propagate and can be
                     * listened to on the parent element.
                     */
                    var item = e.get('target');
                    // A click on the drop-down list title does not need to be processed.
                    if (item !== toolsControl) {
                        let target = null;
                        switch (item.data.get('id')) {
                            case 0:
                                target = "https://waze.com/ul?ll=" + selectedCoords[0] + "," + selectedCoords[1] + "&z=10";
                                break;
                            case 1:
                                target = "http://maps.google.com/maps?q=" + selectedCoords[0] + "," + selectedCoords[1];
                                break;
                            case 2:
                                target = "https://t.me/share/url?url={{route('crypto_map.places')}}/?coordinates=" + selectedCoords[0] + "," + selectedCoords[1] + "&text=موقعیت مکانی پذیرنده رمزارز روی نقشه";
                                break;
                            case 3:
                                target = "whatsapp://send?text=نمایش پذیرنده رمزارز روی نقشه در آدرس {{route('crypto_map.places')}}/crypto-map?coordinates=" + selectedCoords[0] + "," + selectedCoords[1];
                                break;
                        }
                        window.location.href = target;
                    }

                });

                let listBoxItems = @json($categories)
                        .map(function (title) {
                            return new ymaps.control.ListBoxItem({
                                data: {
                                    content: title.name,
                                    type:title.type
                                },
                                state: {
                                    selected: true
                                }
                            })
                        }),
                    reducer = function (filters, filter) {
                        filters[filter.data.get('type')] = filter.isSelected();
                        return filters;
                    },
                    // Now creating the drop-down list with 5 items.
                    listBoxControl = new ymaps.control.ListBox({
                        data: {
                            content: 'دسته بندی',
                            title: 'محدودسازی نقاط نمایش داده شده روی نقشه'
                        },
                        items: listBoxItems,
                        state: {
                            // Indicates that the list is expanded.
                            expanded: true,
                            filters: listBoxItems.reduce(reducer, {})
                        }
                    });
                myMap.controls.add(listBoxControl);

                // Adding tracking to the indicator to check if a list item is selected.
                listBoxControl.events.add(['select', 'deselect'], function (e) {
                    let listBoxItem = e.get('target');
                    let filters = ymaps.util.extend({}, listBoxControl.state.get('filters'));
                    filters[listBoxItem.data.get('type')] = listBoxItem.isSelected();
                    listBoxControl.state.set('filters', filters);
                });

                let filterMonitor = new ymaps.Monitor(listBoxControl.state);
                filterMonitor.add('filters', function (filters) {
                    // Applying the filter.
                    loadingObjectManager.setFilter(getFilterFunction(filters));
                });

                function getFilterFunction(categories) {
                    return function (obj) {
                        let content = obj.cat;
                        return categories[content]
                    }
                }
            }
        });


    </script>
@endpush
@section('page_title','نقشه خودپرداز و صرافی های ارز دیجیتال')
@section('body')
    <div id="help-modal" class="modal text-dark" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">راهنمای استفاده از نقشه</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>نقشه ای که مشاهده می کنید شامل تمامی پذیرندگان رمزارز ها در سراسر جهان است.</p>
                    <hr>
                    <p>این پذیرندگان شامل دستگاه های خودپرداز رمزارز و سایر مکان هایی می شود که به نوعی معاملات رمزارز انجام می دهند یا حتی در ازای ارائه خدمات رمزارز می پذیرند.</p>
                    <hr>
                    <p>با توجه به این که این اطلاعات به صورت آزادانه از سوی منبع قابل ویرایش است، ممکن است صحیح نباشد.</p>
                    <hr>
                    <p>جهت مشاهده یک سری مکان های خاص مثلا فقط خودپرداز های رمزارز، از بخش دسته بندی مکان ها را محدود کنید.</p>
                    <hr>
                    <p>با کلیک بر روی هر نقطه که بر روی نقشه نشان داده شده است، مشخصات کامل مکان را خواهید دید.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row no-gutters">
            <div class="col-md-12 mt-3 mb-1">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb border-light bg-transparent">
                        <li class="breadcrumb-item"><a href="{{route('homepage')}}">صفحه اصلی</a></li>
                        <li class="breadcrumb-item active" aria-current="page">خودپرداز و صرافی های ارز دیجیتال</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row no-gutters">
            <div class="col-md-12">
                <div class="ltr mx-auto border-light rounded mb-3" id="map"></div>
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

