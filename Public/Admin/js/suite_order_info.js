var table = {};
$(function () {

    table = $('#data-list').dataTable({
        "ajax": "admin.php?s=OrderInfo/index/type/ajax.html",
        "deferRender": true,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "stateSave": true,
        "columns": [
            {"data": "id"},
            {"data": "card_num"},
            {"data": "realname"},
            {"data": "id_num"},
            {"data": "tel"},
            {"data": "ctime"},
            {"data": "title"},
            {"data": "order_time"}
        ],
        "columnDefs": [
            {
                "render": function (data, type, row) {
                    return parseInt(data);
                    //return data;
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    var time = new Date(parseInt(data) * 1000);
                    return getLocalTime(data);
                    //return data;
                },
                "targets": 7
            },
            {
                "render": function (data, type, row) {
                    var time = new Date(parseInt(data) * 1000);
                    return getLocalTime(data);
                    //return data;
                },
                "targets": 5
            }
        ]
    });
    $("#data-list tbody").on('click', 'tr', function () {
        $(this).toggleClass('am-primary');
        refresh_btns();
    });


    /*查看*/
    $("button#btn_export").click(function () {
        var starttime = $("#starttime").val();
        var endtime = $("#endtime").val();
        var url = "admin.php?s=OrderInfo/export.html";
        if (starttime) {
            url += "&starttime=" + starttime;
        }
        if (endtime) {
            url += "&endtime=" + endtime;
        }
        location.href = url;
    });

    $("button#query").click(function () {
        var starttime = $("#starttime").val();
        var endtime = $("#endtime").val();

        var url = "admin.php?s=OrderInfo/index/type/ajax.html";
        if (starttime) {
            url += "&starttime=" + starttime;
        }
        if (endtime) {
            url += "&endtime=" + endtime;
        }
        table.fnReloadAjax(url);
    });

    $("button#btn_del").click(function () {
        if (confirm("确认操作吗?")) {
            var selected = $("#data-list tbody tr.am-primary");
            var ids = new Array();
            selected.each(function (index, data) {
                ids.push($(data).children("td:first").text());
            });
            ids = ids.join(",");
            $.get("admin.php?s=OrderInfo/del/ids/" + ids + ".html", function (data) {
                if (data) {
                    topAlert(data.info, true);
                    setTimeout(location.reload(), 500);
                } else {
                    topAlert("未找到!")
                }
            }, 'json');
        }
    });
});


function refresh_btns() {
    var has_selected = $("#data-list tbody tr.am-primary").length;
    if (has_selected > 0) {
        $("button.need_select").removeClass('am-disabled');
    } else {
        $("button.need_select").addClass('am-disabled');
    }
}
