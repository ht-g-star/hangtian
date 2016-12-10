$(function () {
    var table = $('#data-list').dataTable({
        "ajax": "admin.php?s=Company/index/type/ajax.html",
        "deferRender": true,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        stateSave: true,
        "columns": [
            {"data": "id"},
            {"data": "code"},
            {"data": "name"},
            {"data": "username"},
            {"data": "contact"},
            {"data": "contact_tel"},
            //{"data": "balance"},
            //{"data": "last_login_time"},
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
                     if (data == 1) {
                        return "<span class='am-text-success'>正常</span>";
                    } else if (data == 0) {
                        return "<span class='am-text-danger'>禁用</span>";
                    } else {
                        return "<span class='am-text-warning'>异常</span>";
                    }
                },
                "targets": 6
            }
        ]
    });
    $("#data-list tbody").on('click', 'tr', function () {
        $(this).toggleClass('am-primary');
        refresh_btns();
    });

    $('#btn_edit').click(function () {
        var selected = $("#data-list tbody tr.am-primary");
        if (selected.length != 1) {
            topAlert("请选择一条进行编辑!")
        } else {
            var id = selected.children('td:first').text();
            $.get("admin.php?s=Company/index/type/getOne/id/" + id + ".html", function (data) {
                if (data) {
                    $("#pop_edit input[name='id']").val(id);
                    $("#pop_edit input[name='name']").val(data.name);
                    $("#pop_edit input[name='code']").val(data.code);
                    $("#pop_edit input[name='username']").val(data.username);
                    $("#pop_edit input[name='contact']").val(data.contact);
                    $("#pop_edit input[name='contact_tel']").val(data.contact_tel);
                    $("#pop_edit input[name='status'][value=" + data.status + "]").attr("checked", "checked");
                    $('#pop_edit').modal();
                } else {
                    topAlert("未找到!")
                }

            }, 'json');
        }
    });


    $("button#btn_del").click(function () {
        if (confirm("确认操作吗?")) {
            var selected = $("#data-list tbody tr.am-primary");
            var ids = new Array();

            selected.each(function (index, data) {
                ids.push($(data).children("td:first").text());
            });
            ids = ids.join(",");
            $.get("admin.php?s=Company/del/ids/" + ids + ".html", function (data) {
                if (data) {
                    topAlert(data.info, true);
                    setTimeout(location.reload(), 500);
                } else {
                    topAlert("未找到!")
                }
            }, 'json');
        }
    });

    $("button#btn_repass").click(function () {
        var selected = $("#data-list tbody tr.am-primary");
        if (selected.length != 1) {
            topAlert("请选择一条进行操作!")
        } else {
            var id = selected.children('td:first').text();
            $.get("admin.php?s=Company/index/type/getOne/id/" + id + ".html", function (data) {
                if (data) {
                    $("#pop_repass input").val('');
                    $("#pop_repass input[name='id']").val(id);
                    $('#pop_repass').modal();
                } else {
                    topAlert("未找到!")
                }

            }, 'json');
        }



    });

    /*查看*/
    $("button#btn_view").click(function () {
        var selected = $("#data-list tbody tr.am-primary");
        //var ids = new Array();
        if (selected.length != 1) {
            topAlert("请选择一个进行查看!");
        } else {
            var id = selected.children('td:first').text();
            $.get("admin.php?s=Company/index/type/getOne/id/" + id + ".html", function (data) {
                if (data) {
                    $("#pop_info label.val").text('');
                    for (var x in data) {
                        if (x == 'balance') {
                            data['balance'] = "可用:" + (parseFloat(data['balance'])).toFixed(2) ;
                        }
                        $("#pop_info label." + x).text(data[x]);
                    }

                    //会员消费
                    $.get("admin.php?s=Company/index/type/getConsume_log/id/" + id + ".html", function (data) {
                        if (data) {
                            if ($.fn.dataTable.isDataTable("#pop_info table.data-table")) {
                                table = $("#pop_info table.data-table").DataTable();
                                table.destroy();
                            }
                            $('#pop_info table.data-table').dataTable({
                                data: data,
                                columns: [
                                    {title: "时间", data: "ctime"},
                                    {title: "金额", data: "money"},
                                    {title: "缘由", data: "reason"},
                                    {title: "备注", data: "remark"},
                                ]
                            });
                        } else {
                            $('#pop_info table.data-table').html("<tr><td>无数据!</td></tr>");
                        }
                    }, 'json');


                    $('#pop_info').modal();
                } else {
                    topAlert("未找到!")
                }

            }, 'json');


        }

    });


    //批量编辑按钮判断
    $("div#pop_bat_edit select#bat_edit_type").change(function () {
        var self = this;
        var field = $(self).val();
        switch (field) {
            case 'company':
            case 'dept':
            case 'position':
                $("div#pop_bat_edit div.bat_value_div").html('<input type="text" name="' + field + '" id="bat_value"  required placeholder="请输入值">');
                break;
            case 'is_onsite':
                $("div#pop_bat_edit div.bat_value_div").html("<label class='am-radio-inline'><input type='radio' name='is_onsite' value='0' />否</label><label class='am-radio-inline'><input type='radio'  name='is_onsite' value='1' />是</label>");
                break;
            case 'rank':
                $("div#pop_bat_edit div.bat_value_div").html("<select name='rank'><option value='0'>普通会员</option><option value='1'>内部会员</option><option value='2'>VIP</option></select>    ");
                break;
        }
    });


    //批量调整金额
    $('button#btn_balance').click(function () {
        var selected = $("#data-list tbody tr.am-primary");
        if (selected.length != 1) {
            topAlert("请选择一个进行查看!");
            return;
        }
        //批量调整
        var id = selected.children('td:first').text();
        $.get("admin.php?s=Company/index/type/getOne/id/" + id +".html", function (data) {
            if (data) {
                $("#pop_balance div.selected_person").html(data.name);
                $("#pop_balance form").prepend("<input type='hidden' name='id' value='" + id + "'/>");
                $("#pop_balance").modal();
            } else {
                topAlert("有用户状态异常或者不存在!")
            }
        }, 'json');
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
