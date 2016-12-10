/**
 * Created by litao on 15/11/4.
 */

var dt = {};
var selected_rows = new Array();
$(function () {
    dt = $('#data-list').dataTable({
        "ajax": "admin.php?s=Member/index/type/ajax.html",
        "deferRender": true,
        "processing": true,
        "serverSide": true,
        "lengthMenu": [[10, 25, 50,100], [10, 25, 50, 100]],
        "stateSave": true,
        "searchDelay": 2000,
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
            {"data": "level"},
            {"data": "married"},
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
                        return "<span class='am-text-warning'>异常</span>";
                    }
                },
                "targets": 14
            }
        ]
    });
    $("#data-list tbody").on('click', 'tr', function () {
        var id = $(this).children("td:first-child").text();
        if ($(this).hasClass('am-primary')) {
            selected_rows = del_key(id, selected_rows);
            //selected_rows.splice(id, 1);

        } else {
            selected_rows[id] = id;
        }
        refresh_btns();
        refresh_rows();
    });

    $("button#check_all").click(function () {
        selected_rows = new Array();
        var all_data = dt.DataTable().data();
        for (var x in all_data) {
            if (isNaN(x)) {
                continue;
            }
            selected_rows[all_data[x].id] = all_data[x].id;
        }
        refresh_rows();
    });

    $("button#check_reverse").click(function () {
        var all_data = dt.DataTable().data();
        var reverse_data = new Array();
        for (var x in all_data) {
            if (isNaN(x)) {
                continue;
            }
            if (!selected_rows[all_data[x].id]) {
                reverse_data[all_data[x].id] = all_data[x].id;
            }
        }
        selected_rows = reverse_data;
        refresh_rows();
    });

    $("button#send_result").click(function () {
        if (count(selected_rows) <= 0) {
            topAlert("至少选择一条!", 0);
            return;
        }
        var ids = "";
        for (var x in selected_rows) {
            if (isNaN(x)) {
                continue;
            }
            ids += selected_rows[x] + ",";
        }
        $.post("admin.php?s=SuiteOrder/manual.html", {'ids': ids}, function (data) {
            topAlert(data.info, data.status);
        }, 'json')
    });


    $('#data-list').on('draw.dt', function () {
        //var info = dt.DataTable().page.info();
        //console.log(info)
        refresh_rows();
    });

    $("button#query").click(function () {
        var company = $("#company").val();
        var is_onsite = $("#is_onsite").val();
        var sex = $("#sex").val();
        var rank = $("#rank").val();
        var level = $("#level").val();
        var married = $("#married").val();
        var url = "admin.php?s=Member/index/type/ajax.html";
        if (company) {
            url += "&company=" + company;
        }
        if (is_onsite != '') {
            url += "&is_onsite=" + is_onsite;
        }
        if (sex != '') {
            url += "&sex=" + sex;
        }
        if (rank != '') {
            url += "&rank=" + rank;
        }
        if (level != '') {
            url += "&level=" + level;
        }

        if (married != '') {
            url += "&married=" + married;
        }
        dt.fnReloadAjax(url);

        selected_rows = new Array();
        refresh_rows();
    });


});

function refresh_rows() {
    $("#data-list tbody tr td:first-child").each(function (index, row) {
        var id = $(this).text();
        if (selected_rows[id]) {
            if (!$(this).parents("tr").hasClass('am-primary')) {
                $(this).parents("tr").addClass("am-primary");
            }
        } else {
            $(this).parents("tr").removeClass("am-primary");
        }
        $("#count_selected").html(count(selected_rows));
    });
}


function refresh_btns() {
    var has_selected = $("#data-list tbody tr.am-primary").length;
    if (has_selected > 0) {
        $("button.need_select").removeClass('am-disabled');
    } else {
        $("button.need_select").addClass('am-disabled');
    }
}

function count(arr) {
    var count = 0;
    for (x in arr) {
        if (isNaN(x)) {
            continue;
        }
        if (arr[x]) {
            count++;
        }
    }
    return count;
}

function del_key(key, arr) {
    var new_arr = new Array();
    for (x in arr) {
        if (isNaN(x) || key == x) {
            continue;
        }
        new_arr[x] = x;
    }
    return new_arr;
}