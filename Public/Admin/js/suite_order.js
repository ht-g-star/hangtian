$(function () {
    var table = $('#data-list').dataTable({
        "ajax": "admin.php?s=SuiteOrder/index/type/ajax.html",
        "deferRender": true,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "stateSave": true,
        "columns": [
            {"data": "order_no"},
            {"data": "card_num"},
            {"data": "realname"},
            {"data": "title"},
            {"data": "cost"},
            {"data": "period"},
            {"data": "ctime"},
            {"data": "status"},
            {"data": "name2"},
            {"data": "sex2"},
            {"data": "id_num2"},
            {"data": "tel2"}
        ],
        "columnDefs": [
            {
                "render": function (data, type, row) {
                    if (data == 1) {
                        return "<span class='am-text-success'>待支付</span>";
                    } else if (data == 0) {
                        return "<span class='am-text-danger'>已取消</span>";
                    } else if (data == 10) {
                        return "<span class='am-text-warning'>已支付待登记</span>";
                    } else if (data == 11) {
                        return "<span class='am-text-warning'>已登记待体检</span>";
                    } else if (data == 20) {
                        return "<span class='am-text-warning'>已完成</span>";
                    }
                },
                "targets": 7
            },
            {
                "render": function (data, type, row) {
                    var time = new Date(parseInt(data) * 1000);
                    return time.toLocaleString();
                    //return data;
                },
                "targets": 6
            },
            {
                "render": function (data, type, row) {
                    return data + " " + row.time;
                },
                "targets": 5
            },
            {
                "render": function (data, type, row) {
                    if (data == 1) {
                        return "男";
                    } else if (data == 0) {
                        return "女";
                    } else {
                        return "未知";
                    }
                },
                "targets": 9
            }
        ]
    });
    $("#data-list tbody").on('click', 'tr', function () {
        $(this).toggleClass('am-primary');
        refresh_btns();
    });


    $("button#btn_reg").click(function () {
        if (confirm("确认操作吗?")) {
            var selected = $("#data-list tbody tr.am-primary");
            var ids = new Array();
            selected.each(function (index, data) {
                ids.push($(data).children("td:first").text());
            });
            ids = ids.join(",");
            $.get("admin.php?s=SuiteOrder/index/type/reg/ids/" + ids + ".html", function (data) {
                if (data) {
                    topAlert(data.info, true);
                    setTimeout(location.reload(), 500);
                } else {
                    topAlert("未找到!")
                }
            }, 'json');
        }
    });

    $("button#btn_finish").click(function () {
        if (confirm("确认操作吗?")) {
            var selected = $("#data-list tbody tr.am-primary");
            var ids = new Array();
            selected.each(function (index, data) {
                ids.push($(data).children("td:first").text());
            });
            ids = ids.join(",");
            $.get("admin.php?s=SuiteOrder/index/type/finish/ids/" + ids + ".html", function (data) {
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
