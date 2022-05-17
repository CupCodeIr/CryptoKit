$(document).ready(function () {


    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
    let coins_table;
    $('#collapseOne').on('shown.bs.collapse', function () {
        if (exchanges_table == null)
            coins_table = $('#wallet-coins-table').DataTable(
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


    let dataTable_translation = {
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

    };


});



