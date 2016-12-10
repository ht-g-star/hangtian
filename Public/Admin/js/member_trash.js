/**
 * Created by litao on 15/11/4.
 */
$(function () {
    var table = $('#data-list').dataTable({
        "ajax": "admin.php?s=MemberTrash/index/type/ajax.html",
        "deferRender": true,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        stateSave: true,
        "columns": [
            {"data": "id"},
            {"data": "card_num"},
            {"data": "company"},
            {"data": "dept"},
            {"data": "position"},
            {"data": "is_onsite"},
            {"data": "realname"},
            {"data": "sex"},
            {"data": "id_num"},
            {"data": "mobile"},
            {"data": "balance"},
            {"data": "rank"},
            {"data": "status"}
        ],
        "columnDefs": [
            {
                "render": function (data, type, row) {
                    return parseInt(data);
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    if (data == '') {
                        return '暂无';
                    } else {
                        return data;
                    }
                },
                "targets": 1
            },
            {
                "render": function (data, type, row) {
                    if (data == 0) {
                        return "否";
                    } else {
                        return '是';
                    }
                },
                "targets": 5
            },
            {
                "render": function (data, type, row) {
                    if (data == 0) {
                        return "女";
                    } else {
                        return '男';
                    }
                },
                "targets": 7
            },
            {
                "render": function (data, type, row) {
                    if (data == 0) {
                        return "普通";
                    } else if (data == 1) {
                        return '内部会员';
                    } else if (data == 2) {
                        return "vip"
                    } else {
                        return "未知";
                    }
                },
                "targets": 11
            },
            {
                "render": function (data, type, row) {
                    if (data == 0) {
                        return "<span class='am-text-default'>未开卡</span>";
                    } else if (data == 1) {
                        return "<span class='am-text-success'>正常</span>";
                    } else if (data == 10) {
                        return "<span class='am-text-danger'>挂失</span>";
                    } else {
                        return "<span class='am-text-warning'>删除</span>";
                    }
                },
                "targets": 12
            }
        ]
    });
    $("#data-list tbody").on('click', 'tr', function () {
        $(this).toggleClass('am-primary');
        refresh_btns();
    });


    $("#btn_recycle").click(function () {
        if (confirm("您选择的会员将会回复到会员列表中,是否确认?")) {
            var selected = $("#data-list tbody tr.am-primary");
            var ids = [];
            selected.each(function (index, data) {
                ids.push($(data).children("td:first").text());
            })
            ids = ids.join(",");

            $.get("admin.php?s=MemberTrash/recycle/ids/" + ids + ".html", function (data) {
                if (data) {
                    topAlert(data.info, data.status);
                    location.reload();
                } else {
                    topAlert("未找到该用户,请刷新网页重试!")
                }
            }, 'json');
        }
    });

    $("#btn_del").click(function () {
        if (confirm("您选择的会员将被永久删除,无法回复,是否确认?")) {
            var selected = $("#data-list tbody tr.am-primary");
            var ids = [];
            selected.each(function (index, data) {
                ids.push($(data).children("td:first").text());
            })
            ids = ids.join(",");

            $.get("admin.php?s=MemberTrash/del/ids/" + ids + ".html", function (data) {
                if (data) {
                    topAlert(data.info, data.status);
                    location.reload();
                } else {
                    topAlert("未找到该用户,请刷新网页重试!")
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

