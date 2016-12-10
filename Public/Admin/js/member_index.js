/**
 * Created by litao on 15/11/4.
 */
$(function () {
    var table = $('#data-list').dataTable({
        "ajax": "/index.php?s=/Admin/Member/index/type/ajax.html",
        "deferRender": true,
        "processing": true,
        "serverSide": true,
        "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
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
                    return parseFloat(data).toFixed(2)
                },
                "targets": 10
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
        $(this).toggleClass('am-primary');
        refresh_btns();
    });

    $('#btn_edit').click(function () {
        var selected = $("#data-list tbody tr.am-primary");
        if (selected.length != 1) {
            //批量调整
            var ids = [];
            selected.each(function (index, data) {
                ids.push($(data).children("td:first").text());
            });
            ids = ids.join(",");

            $.get("admin.php?s=Member/index/type/getBat/ids/" + ids, function (data) {
                if (data) {
                    var str_name = [];
                    for (var i = 0; i < data.length; i++) {
                        str_name.push(data[i].realname);
                    }
                    $("#pop_bat_edit div.selected_person").html(str_name.join(","));
                    $("#pop_bat_edit form input[name='ids']").remove();
                    $("#pop_bat_edit form").prepend("<input type='hidden' name='ids' value='" + ids + "'/>");
                    $("#pop_bat_edit").modal();
                    console.log(data);
                } else {
                    topAlert("有用户状态异常或者不存在!")
                }
            }, 'json');
        } else {
            var id = selected.children('td:first').text();
            $.get("admin.php?s=Member/index/type/getOne/id/" + id + ".html", function (data) {
                if (data) {
                    $("#pop_edit input[name='id']").val(id);
                    $("#pop_edit input[name='realname']").val(data.realname);
                    $("#pop_edit input[name='company']").val(data.company);
                    $("#pop_edit input[name='dept']").val(data.dept);
                    $("#pop_edit input[name='position']").val(data.position);
                    $("#pop_edit input[name='sex'][value=" + data.sex + "]").attr("checked", "checked");
                    $("#pop_edit input[name='is_onsite'][value=" + data.is_onsite + "]").attr("checked", "checked");
                    $("#pop_edit input[name='mobile']").val(data.mobile);
                    $("#pop_edit input[name='id_num']").val(data.id_num);
                    $("#pop_edit select[name='rank']").val(data.rank);
                    $("#pop_edit select[name='level']").val(data.level);
                    $("#pop_edit select[name='married']").val(data.married);
                    $("#pop_edit textarea[name='service']").val(data.service);

                    $('#pop_edit').modal();
                } else {
                    topAlert("未找到!")
                }

            }, 'json');
        }
    });

    $("button#query").click(function () {
        var company = $("#company").val();
        console.log(company);
        var url = "admin.php?s=Member/index/type/ajax.html&company=" + company;
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
            $.get("admin.php?s=Member/del/ids/" + ids + ".html", function (data) {
                if (data) {
                    topAlert(data.info, true);
                    setTimeout(location.reload(), 500);
                } else {
                    topAlert("未找到!")
                }
            }, 'json');
        }
    });

    $("button#btn_open_card").click(function () {
        var selected = $("#data-list tbody tr.am-primary");
        //var ids = new Array();
        if (selected.length != 1) {
            topAlert("请选择一个进行开卡!");
        } else {
            var id = selected.children('td:first').text();
            $.get("admin.php?s=Member/index/type/getOne/id/" + id + ".html", function (data) {
                if (data) {
                    if (data.status != 0) {
                        topAlert("只有未开卡状态才能开卡!");
                        return false;
                    }
                    $("#pop_open input").val('');
                    $("#pop_open input[name='realname']").val(data.realname);
                    $("#pop_open input[name='id']").val(data.id);
                    $('#pop_open').modal();
                } else {
                    topAlert("未找到!")
                }

            }, 'json');
        }


    });

    $("button#btn_recard").click(function () {
        var selected = $("#data-list tbody tr.am-primary");
        //var ids = new Array();
        if (selected.length != 1) {
            topAlert("请选择一个进行补卡!");
        } else {
            var id = selected.children('td:first').text();
            $.get("admin.php?s=Member/index/type/getOne/id/" + id + ".html", function (data) {
                if (data) {
                    if (data.status != 10) {
                        topAlert("只有挂失状态才能补卡!");
                        return false;
                    }
                    $("#pop_recard input").val('');
                    $("#pop_recard input[name='realname']").val(data.realname);
                    $("#pop_recard input[name='id']").val(data.id);
                    $('#pop_recard').modal();
                } else {
                    topAlert("未找到!")
                }

            }, 'json');
        }

    });


    $("button#btn_lost").click(function () {
        var selected = $("#data-list tbody tr.am-primary");
        if (selected.length != 1) {
            topAlert("请选择一个进行挂失!");
        } else {
            if (confirm("确认操作吗?")) {
                var id = selected.children('td:first').text();
                $.get("admin.php?s=Member/lost/id/" + id + ".html", function (data) {
                    if (data) {
                        topAlert(data.info, data.status);
                        if (data.status) {
                            setTimeout(location.reload(), 500);
                        }
                    } else {
                        topAlert("未找到!");
                    }
                }, 'json');
            }
        }

    });

    $("button#btn_charge").click(function () {
        $("#pop_charge input").val('');
        $('#pop_charge').modal();
    });


    $("button#btn_froze_money").click(function () {
        $("#pop_froze_money input").val('');
        $("#pop_froze_money input[name='money']").removeClass('am-field-error');
        $('#pop_froze_money').modal();

    });

    $("#pop_froze_money form").submit(function () {
        var balance = $("#pop_froze_money input[name='balance']").val();
        var money = $("#pop_froze_money input[name='money']").val();
        if (parseFloat(money) > parseFloat(balance)) {
            topAlert("余额不足!");
            return false;
        }
    });
    $("#pop_froze_money input[name='money']").blur(function () {
        $(this).addClass('am-field-error');
        var balance = $("#pop_froze_money input[name='balance']").val();
        var money = $("#pop_froze_money input[name='money']").val();
        if (parseFloat(money) > parseFloat(balance)) {
            topAlert("余额不足!");
            return false;
        } else {
            $("#pop_froze_money input[name='money']").removeClass('am-field-error');
        }
    });

    $("#pop_froze_money input[name='card_num']").blur(function () {
        var self = $(this);
        $.get("admin.php?s=Member/index/type/getOneByCardNum/card_num/" + self.val() + ".html", function (data) {
            if (data) {
                if (data.status != 1) {
                    topAlert("只有正常状态才能使用!");
                    return false;
                }
                if (!data.password) {
                    topAlert("用户未设置密码,请先设置密码!");
                    need_change_pass('pop_froze_money');
                    return false;
                }
                $("#pop_froze_money input[name='realname']").val(data.realname);
                $("#pop_froze_money input[name='balance']").val((parseFloat(data.balance)).toFixed(2));
                $("#pop_froze_money input[name='frozen_balance']").val(data.frozen_balance);
                $("#pop_froze_money input[name='id']").val(data.id);
            } else {
                topAlert("未找到!")
            }

        }, 'json');
    });


    $("#pop_froze_money button#release_btn").click(function () {

        var balance = $("#pop_froze_money input[name='balance']").val();
        var frozen_balance = $("#pop_froze_money input[name='frozen_balance']").val();
        if (frozen_balance == 0) {
            topAlert("没有需要解冻的资金!");
        }
        var release_money = $("#pop_froze_money input[name='release_money']").val();
        if (parseFloat(release_money) > parseFloat(frozen_balance)) {
            topAlert("需要解冻的金额大于冻结金额!");
            $("#pop_froze_money input[name='release_money']").addClass('am-field-error');
            return false;
        } else {
            $("#pop_froze_money input[name='release_money']").removeClass('am-field-error');
            var id = $("#pop_froze_money input[name='id']").val();
            if (!id) {
                topAlert("未找到该会员信息!");
                return false;
            }
            if (!release_money) {
                topAlert("请先输入要解冻的金额!");
                return false;
            }
            $.get("admin.php?s=Member/lockmoney/type/release/id/" + id + "/release_money/" + release_money + ".html", function (data) {
                topAlert(data.info, data.status);
                location.reload();
            }, 'json');
        }
    });


    $("button#btn_repass").click(function () {
        $("#pop_repass input").val('');
        $('#pop_repass').modal();

    });
    $("#pop_repass input[name='card_num']").blur(function () {
        var self = $(this);
        $.get("admin.php?s=Member/index/type/getOneByCardNum/card_num/" + self.val() + ".html", function (data) {
            if (data) {
                if (data.status != 1) {
                    topAlert("只有正常状态才能使用!");
                    return false;
                }
                $("#pop_repass input[name='realname']").val(data.realname);
                $("#pop_repass input[name='id']").val(data.id);
            } else {
                topAlert("未找到该用户!")
            }

        }, 'json');
    });
    $("#pop_repass form").submit(function () {
        var password0 = $("#pop_repass input[name='password0']").val();
        if (password0 == '') {
            topAlert("请输入原密码!");
        }
        var password1 = $("#pop_repass input[name='password1']").val();
        var password = $("#pop_repass input[name='password']").val();
        if (password != password1) {
            topAlert("两次密码不一致!");
            return false;
        }
    });


    /*消费*/
    $("#btn_consume").click(function () {
        $("#pop_consume input").val('');
        $("#pop_consume").modal();
    });
    $("#pop_consume input[name='card_num']").blur(function () {
        var self = $(this);
        $.get("admin.php?s=Member/index/type/getOneByCardNum/card_num/" + self.val() + ".html", function (data) {
            if (data) {
                if (data.status != 1) {
                    topAlert("只有正常状态才能使用!");
                    return false;
                }
                if (!data.password) {
                    topAlert("用户未设置密码,请先设置密码!");
                    need_change_pass("pop_consume");
                    return false;
                }
                $("#pop_consume input[name='realname']").val(data.realname);
                $("#pop_consume input[name='balance']").val((parseFloat(data.balance)).toFixed(2));
                $("#pop_consume input[name='frozen_balance']").val(data.frozen_balance);
                $("#pop_consume input[name='id']").val(data.id);
            } else {
                topAlert("未找到!")
            }

        }, 'json');
    });
    $("#pop_consume form").submit(function () {
        var balance = $("#pop_consume input[name='balance']").val();
        var money = $("#pop_consume input[name='money']").val();
        if (parseFloat(money) > parseFloat(balance)) {
            $("#pop_consume input[name='money']").addClass('am-field-error');
            topAlert("余额不足!");
            return false;
        } else {
            $("#pop_consume input[name='money']").removeClass('am-field-error');
            return confirm("确认消费?");
        }
    });
    $("#pop_consume input[name='money']").blur(function () {
        var balance = $("#pop_consume input[name='balance']").val();
        var money = $("#pop_consume input[name='money']").val();
        if (parseFloat(money) > parseFloat(balance)) {
            $("#pop_consume input[name='money']").addClass('am-field-error');
            topAlert("余额不足!");
            return false;
        } else {
            $("#pop_consume input[name='money']").removeClass('am-field-error');
        }
    });


    /*查看会员*/
    $("button#btn_view").click(function () {
        var selected = $("#data-list tbody tr.am-primary");
        //var ids = new Array();
        if (selected.length != 1) {
            topAlert("请选择一个进行查看!");
        } else {
            var id = selected.children('td:first').text();
            $.get("admin.php?s=Member/index/type/getOne/id/" + id + ".html", function (data) {
                if (data) {
                    $("#pop_info label.val").text('');
                    for (var x in data) {
                        if (x == 'rank') {
                            if (data[x] == 0) {
                                data[x] = "普通会员";
                            } else if (data[x] == 1) {
                                data[x] = "航天内部会员";
                            } else if (data[x] == 2) {
                                data[x] = "VIP会员";
                            } else {
                                data[x] = "错误!";
                            }
                        }
                        if (x == 'sex') {
                            if (data[x] == 0) {
                                data[x] = "女";
                            } else {
                                data[x] = "男";
                            }
                        }
                        if (x == 'balance') {
                            data['balance'] = "可用:" + (parseFloat(data['balance'])).toFixed(2) + ",冻结:" + data['frozen_balance'];
                        }

                        $("#pop_info label." + x).text(data[x]);
                    }

                    //会员消费
                    $.get("admin.php?s=Member/index/type/getConsume_log/id/" + id + ".html", function (data) {
                        if (data) {
                            if ($.fn.dataTable.isDataTable("#pop_info table.data-table")) {
                                table = $("#pop_info table.data-table").DataTable();
                                table.destroy();
                            }
                            $('#pop_info table.data-table').dataTable({
                                data: data,
                                columns: [
                                    {title: "操作人ID", data: "uid"},
                                    {title: "时间", data: "ctime"},
                                    {title: "备注", data: "remark"}
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
            case 'level':
                $("div#pop_bat_edit div.bat_value_div").html('<select id="level" name="level"><option value=""></option><option value="Ⅰ">Ⅰ</option><option value="Ⅱ">Ⅱ</option><option value="Ⅲ">Ⅲ</option><option value="Ⅳ">Ⅳ</option><option value="Ⅴ">Ⅴ</option><option value="Ⅵ">Ⅵ</option><option value="Ⅶ">Ⅶ</option></select>');
                break;
        }
    });
    $("div#pop_bat_edit select#bat_edit_type").change();


    //批量调整金额
    $('#btn_balance').click(function () {
        var selected = $("#data-list tbody tr.am-primary");
        //批量调整
        var ids = [];
        selected.each(function (index, data) {
            ids.push($(data).children("td:first").text());
        })
        ids = ids.join(",");

        $.get("admin.php?s=Member/index/type/getBat/ids/" + ids, function (data) {
            if (data) {
                var str_name = [];
                for (var i = 0; i < data.length; i++) {
                    str_name.push(data[i].realname);
                }
                $("#pop_balance div.selected_person").html(str_name.join(","));
                $("#pop_balance form input[name='ids']").remove();
                $("#pop_balance form").prepend("<input type='hidden' name='ids' value='" + ids + "'/>");
                $("#pop_balance").modal();
            } else {
                topAlert("有用户状态异常或者不存在!")
            }
        }, 'json');
    });


    $("#export").click(function () {
        var href = $(this).data("href");
        if ($("#company").val()) {
            window.location.href = href + "&company=" + $("#company").val();
        } else {
            window.location.href = href;
        }

    });

    $("form").submit(function () {
        $(this).find("button[type='submit']").addClass("am-disabled");
    });
});

function need_change_pass(who) {
    $("#" + who).modal('close');
    $("button#btn_repass").click();

}

function refresh_btns() {
    var has_selected = $("#data-list tbody tr.am-primary").length;
    if (has_selected > 0) {
        $("button.need_select").removeClass('am-disabled');
    } else {
        $("button.need_select").addClass('am-disabled');
    }
}
