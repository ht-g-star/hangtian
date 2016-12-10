$(function () {
    $(".confirm").click(function () {
        if (confirm("确认操作?")) {
            return true;
        } else {
            return false;
        }
    });

});

/**
 *
 * @param msg 消息
 * @param success 是否成功信息
 * @param autoClose 是否自动关闭,默认为true
 */
function topAlert(msg, success, autoClose) {

    if (!success) {
        success = 'am-alert-danger';
    } else {
        success = 'am-alert-success';
    }
    $('<div class="am-alert  top-alert ' + success + ' " data-am-alert><button type="button" class="am-close">&times;</button><p>' + msg + '</p></div>').prependTo('body').alert();
    if (autoClose !== false) {
        setTimeout(function () {
            $('.top-alert').alert('close');
        }, 3000);
    }
}