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

$(window).on('scroll', function () {
    if ($(window).scrollTop() > 50) {
        $('.am-topbar').removeClass('large').addClass('small');
    } else {
        $('.am-topbar').removeClass('small').addClass('large');
    }
});


$('a#tel_btn').on('click', function () {
    var $w = $(window);
    $w.smoothScroll({position: $(document).height() - $w.height()});
});


$(function () {
    $('input, textarea').placeholder();
});