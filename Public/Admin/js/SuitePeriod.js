$(function () {
    $("#suite_id").change(function () {
        var sid = $(this).val();
        location.href = "admin.php?s=/SuitePeriod/index/s_id/" + sid + ".html";
    });
    $("table.calendar td:not(.am-disabled)").css("cursor", "pointer");
    $("table.calendar td:not(.am-disabled)").hover(function () {
        $(this).toggleClass("am-active")
    });
    $("table.calendar td:not(.am-disabled)").click(function () {
        if ($("#suite_id").val() == 0) {
            topAlert("先选择一个套餐!");
            return;
        }
        var day = $.trim($(this).text());
        if (day == '' || day == ' ') {
            return;
        }
        $(this).toggleClass("am-warning");

        var date = $(this).parents("table").children("caption").text() + "-" + day;
        if ($(this).hasClass("am-warning")) {
            $("button.am-hide").removeClass("am-hide");
            $("div#period_list").append('<div class="am-u-sm-3 am-u-end selected d' + date + '"><label>'
                + date
                + '</label><input type="hidden" name="date[]" value="' + date + '" />'
                + '<label>库存:<input type="number" class="am-form-field" value="50" name="total[' + date + ']" /></label>'
                + '</div>');
        } else {
            $("div#period_list div.d" + date).remove();
        }

    });

});
