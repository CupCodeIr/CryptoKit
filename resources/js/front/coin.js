$(document).ready(function () {


    let EFS_obj = $("#exchange-from-symbol");
    let EFI_obj = $("#exchange-from");
    let RB_obj = $("#reverse-icon");
    let ETS_obj = $("#exchange-to-symbol");
    let ETI_obj = $("#exchange-to");
    let direction, efi, eti;

    // $('.exchanges-carousel').slick({
    //     accessibility: false,
    //     autoplay: true,
    //     arrows: false,
    //     focusOnSelect: true,
    //     infinite: true,
    //     swipeToSlide: true,
    //     rtl: true,
    //     speed: 300,
    //     slidesToShow: 6,
    //     slidesToScroll: 1,
    //     responsive: [
    //         {
    //             breakpoint: 1024,
    //             settings: {
    //                 slidesToShow: 3,
    //                 slidesToScroll: 3,
    //                 infinite: true,
    //             }
    //         },
    //         {
    //             breakpoint: 600,
    //             settings: {
    //                 slidesToShow: 2,
    //                 slidesToScroll: 2
    //             }
    //         },
    //         {
    //             breakpoint: 480,
    //             settings: {
    //                 slidesToShow: 1,
    //                 slidesToScroll: 1
    //             }
    //         }
    //     ]
    // });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    $('.collapse').on('show.bs.collapse', function () {
        // do somethingâ€¦
        !$(".collapse").not(this).each(function () {
            $(this).collapse('hide');
        })
    });
    let exchanges_table, wallets_table, pools_table;
    $('#collapseTwo').on('shown.bs.collapse', function () {
        if (exchanges_table == null)
            exchanges_table = $('#coin-exchanges-table').DataTable(
                {
                    colReorder: true,
                    fixedHeader: {
                        headerOffset: 80
                    },
                    responsive: true,
                    "language": dataTable_translation
                }
            );
        $('#coin-exchanges-table_filter input').addClass('datatable-search-input');

        exchanges_table.columns.adjust().draw();
    });
    $('#collapseThree').on('shown.bs.collapse', function () {
        if (wallets_table == null)
            wallets_table = $('#coin-wallets-table').DataTable(
                {
                    colReorder: true,
                    fixedHeader: {
                        headerOffset: 80
                    },
                    responsive: true,
                    "language": dataTable_translation,
                    visible: false
                }
            );
        $('#coin-wallets-table_filter input').addClass('datatable-search-input');
        wallets_table.columns.adjust().draw();

    });
    $('#collapseFour').on('shown.bs.collapse', function () {
        if (pools_table == null)
            pools_table = $('#coin-pools-table').DataTable(
                {
                    colReorder: true,
                    fixedHeader: {
                        headerOffset: 80
                    },
                    responsive: true,
                    "language": dataTable_translation
                }
            );
        $('#coin-pools-table_filter input').addClass('datatable-search-input');
        pools_table.columns.adjust().draw();


    });

    $('input[id="exchange-from"]').keyup(function () {
            setTimeout(function () {
                calculate()
            }, 1);
        }
    );
    $("#reverse-icon").on('click', function () {
            reverse();
        }
    );

    function calculate() {
        direction = RB_obj.data("direction");
        if (direction === "fromto") {
            efi = EFI_obj.val();
            eti = efi * ETI_obj.data("to");
            ETI_obj.val(number_clean(eti))
        } else {
            efi = EFI_obj.val();
            eti = efi / ETI_obj.data("to");
            ETI_obj.val(number_clean(eti))
        }


    }

    function reverse() {
        direction = RB_obj.data("direction");
        console.log(direction);
        if (direction === "fromto") direction = "tofrom";
        else direction = "fromto";
        $("#reverse-icon").data("direction", direction);
        EFI_obj.val("");
        ETI_obj.val("");
        let temp = EFS_obj.html();
        EFS_obj.html(ETS_obj.html());
        ETS_obj.html(temp);
    }

    function number_clean(number) {

        let string_number = number.toString();
        let floatingPointPosition = string_number.indexOf(".");
        let floating = "";
        let decimal = string_number;
        if (floatingPointPosition !== -1) {
            floating = string_number.substring(floatingPointPosition);
            decimal = string_number.substring(0, floatingPointPosition);
        }
        let dLength = decimal.length;
        if (dLength < 4) return number;
        while (dLength > 3) {
            let leftPart = (decimal.substring(0, dLength - 3));
            let rightPart = (decimal.substring(dLength - 3));
            decimal = leftPart + "," + rightPart;
            dLength -= 3;
        }
        return decimal + floating;


    }

    let dataTable_translation = {
        "sEmptyTable": "Ů‡ŰŚÚ† ŘŻŘ§ŘŻŮ‡â€ŚŘ§ŰŚ ŘŻŘ± Ř¬ŘŻŮ?Ů„ Ů?Ř¬Ů?ŘŻ Ů†ŘŻŘ§Ř±ŘŻ",
        "sInfo": "Ů†Ů…Ř§ŰŚŘ´ _START_ ŘŞŘ§ _END_ Ř§Ř˛ _TOTAL_ Ř±ŘŻŰŚŮ?",
        "sInfoEmpty": "Ů†Ů…Ř§ŰŚŘ´ 0 ŘŞŘ§ 0 Ř§Ř˛ 0 Ř±ŘŻŰŚŮ?",
        "sInfoFiltered": "(Ů?ŰŚŮ„ŘŞŘ± Ř´ŘŻŮ‡ Ř§Ř˛ _MAX_ Ř±ŘŻŰŚŮ?)",
        "sInfoPostFix": "",
        "sInfoThousands": ",",
        "sLengthMenu": "Ů†Ů…Ř§ŰŚŘ´ _MENU_ Ř±ŘŻŰŚŮ?",
        "sLoadingRecords": "ŘŻŘ± Ř­Ř§Ů„ Ř¨Ř§Ř±ÚŻŘ˛Ř§Ř±ŰŚ...",
        "sProcessing": "ŘŻŘ± Ř­Ř§Ů„ ŮľŘ±ŘŻŘ§Ř˛Ř´...",
        "sSearch": "",
        "searchPlaceholder": "Ř¬ŘłŘŞŘ¬Ů? Ú©Ů†ŰŚŘŻ...",
        "sZeroRecords": "Ř±Ú©Ů?Ř±ŘŻŰŚ Ř¨Ř§ Ř§ŰŚŮ† Ů…Ř´Ř®ŘµŘ§ŘŞ ŮľŰŚŘŻŘ§ Ů†Ř´ŘŻ",
        "oPaginate": {
            "sFirst": "Ř¨Ř±ÚŻŮ‡â€ŚŰŚ Ů†Ř®ŘłŘŞ",
            "sLast": "Ř¨Ř±ÚŻŮ‡â€ŚŰŚ Ř˘Ř®Ř±",
            "sNext": "Ř¨ŘąŘŻŰŚ",
            "sPrevious": "Ů‚Ř¨Ů„ŰŚ"
        },
        "oAria": {
            "sSortAscending": ": Ů?ŘąŘ§Ů„ ŘłŘ§Ř˛ŰŚ Ů†Ů…Ř§ŰŚŘ´ Ř¨Ů‡ ŘµŮ?Ř±ŘŞ ŘµŘąŮ?ŘŻŰŚ",
            "sSortDescending": ": Ů?ŘąŘ§Ů„ ŘłŘ§Ř˛ŰŚ Ů†Ů…Ř§ŰŚŘ´ Ř¨Ů‡ ŘµŮ?Ř±ŘŞ Ů†Ř˛Ů?Ů„ŰŚ"
        },

    };


});



