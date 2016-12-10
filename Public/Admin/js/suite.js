$(function () {
    var table = $('#data-list').dataTable({
        "ajax": "admin.php?s=Suite/index/type/ajax.html",
        "deferRender": true,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "stateSave": true,
        "columns": [
            {"data": "id"},
            {"data": "title"},
            {"data": "code"},
            {"data": "sort"},
            {"data": "buy_count"},
            {"data": "view_count"},
            {"data": "status"}
        ],
        "columnDefs": [
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
            },
            {
                "render": function (data, type, row) {
                    return parseInt(data);
                },
                "targets": 3
            }
        ]
    });
    $("#data-list tbody").on('click', 'tr', function () {
        $(this).toggleClass('am-primary');
        refresh_btns();
    });
    $("#btn_add").click(function () {
        location.href = "admin.php?s=Suite/index/type/add.html";
    });

    $('#btn_edit').click(function () {
        var selected = $("#data-list tbody tr.am-primary");
        if (selected.length != 1) {
            topAlert("请选择一条进行编辑!")
        } else {
            var id = selected.children('td:first').text();
            location.href = "admin.php?s=Suite/index/type/edit/id/" + id + ".html";
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
            $.get("admin.php?s=Suite/del/ids/" + ids + ".html", function (data) {
                if (data) {
                    topAlert(data.info, true);
                    setTimeout(location.reload(), 500);
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
            location.href = "admin.php?s=Suite/index/type/getOne/id/" + id + ".html";
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
