var car2 = {
    _events: {},
    _windowHeight: $(window).height(),
    _windowWidth: $(window).width(),
    _rotateNode: $('.p-ct'),
    _page: $('.m-page'),
    _pageNum: $('.m-page').size(),
    _pageNow: 0,
    _pageNext: null,
    _touchStartValY: 0,
    _touchDeltaY: 0,
    _moveStart: true,
    _movePosition: null,
    _movePosition_c: null,
    _mouseDown: false,
    _moveFirst: true,
    _moveInit: false,
    _firstChange: false,
    _audioNode: $('.u-audio'),
    _audio: null,
    _audio_val: true,
    _elementStyle: document.createElement('div').style,
    _UC: RegExp("Android").test(navigator.userAgent) && RegExp("UC").test(navigator.userAgent) ? true : false,
    _weixin: RegExp("MicroMessenger").test(navigator.userAgent) ? true : false,
    _iPhoen: RegExp("iPhone").test(navigator.userAgent) || RegExp("iPod").test(navigator.userAgent) || RegExp("iPad").test(navigator.userAgent) ? true : false,
    _Android: RegExp("Android").test(navigator.userAgent) ? true : false,
    _IsPC: function () {
        var userAgentInfo = navigator.userAgent;
        var Agents = new Array("Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod");
        var flag = true;
        for (var v = 0; v < Agents.length; v++) {
            if (userAgentInfo.indexOf(Agents[v]) > 0) {
                flag = false;
                break;
            }
        }
        return flag;
    },
    _isload: false,
    _audio_src: "",
    _isOwnEmpty: function (obj) {
        for (var name in obj) {
            if (obj.hasOwnProperty(name)) {
                return false;
            }
        }
        return true;
    },
    _WXinit: function (callback) {
        if (typeof window.WeixinJSBridge == 'undefined' || typeof window.WeixinJSBridge.invoke == 'undefined') {
            setTimeout(function () {
                this.WXinit(callback);
            }, 200);
        } else {
            callback();
        }
    },
    _vendor: function () {
        var vendors = ['t', 'webkitT', 'MozT', 'msT', 'OT'], transform, i = 0, l = vendors.length;
        for (; i < l; i++) {
            transform = vendors[i] + 'ransform';
            if (transform in this._elementStyle)return vendors[i].substr(0, vendors[i].length - 1);
        }
        return false;
    },
    _prefixStyle: function (style) {
        if (this._vendor() === false)return false;
        if (this._vendor() === '')return style;
        return this._vendor() + style.charAt(0).toUpperCase() + style.substr(1);
    },
    _hasPerspective: function () {
        var ret = this._prefixStyle('perspective')in this._elementStyle;
        if (ret && 'webkitPerspective'in this._elementStyle) {
            this._injectStyles('@media (transform-3d),(-webkit-transform-3d){#modernizr{left:9px;position:absolute;height:3px;}}', function (node, rule) {
                ret = node.offsetLeft === 9 && node.offsetHeight === 3;
            });
        }
        return !!ret;
    },
    _translateZ: function () {
        if (car2._hasPerspective) {
            return ' translateZ(0)';
        } else {
            return '';
        }
    },
    _injectStyles: function (rule, callback, nodes, testnames) {
        var style, ret, node, docOverflow, div = document.createElement('div'), body = document.body, fakeBody = body || document.createElement('body'), mod = 'modernizr';
        if (parseInt(nodes, 10)) {
            while (nodes--) {
                node = document.createElement('div');
                node.id = testnames ? testnames[nodes] : mod + (nodes + 1);
                div.appendChild(node);
            }
        }
    },
    _handleEvent: function (type) {
        if (!this._events[type]) {
            return;
        }
        var i = 0, l = this._events[type].length;
        if (!l) {
            return;
        }
        for (; i < l; i++) {
            this._events[type][i].apply(this, [].slice.call(arguments, 1));
        }
    },
    _on: function (type, fn) {
        if (!this._events[type]) {
            this._events[type] = [];
        }
        this._events[type].push(fn);
    },
    page_start: function () {
        car2._page.on('touchstart', car2.page_touch_start);
        car2._page.on('touchmove', car2.page_touch_move);
        car2._page.on('touchend', car2.page_touch_end);
    },
    page_stop: function () {
        car2._page.off('touchstart');
        car2._page.off('touchmove');
        car2._page.off('touchend');
    },
    page_touch_start: function (e) {
        if (!car2._moveStart)return;
        if (e.type == "touchstart") {
            car2._touchStartValY = window.event.touches[0].pageY;
        } else {
            car2._touchStartValY = e.pageY || e.y;
            car2._mouseDown = true;
        }
        car2._moveInit = true;
        car2._handleEvent('start');
    },
    page_touch_move: function (e) {
        e.preventDefault();
        if (!car2._moveStart)return;
        if (!car2._moveInit)return;
        var $self = car2._page.eq(car2._pageNow), h = parseInt($self.height()), moveP, scrollTop, node = null, move = false;
        if (e.type == "touchmove") {
            moveP = window.event.touches[0].pageY;
            move = true;
        } else {
            if (car2._mouseDown) {
                moveP = e.pageY || e.y;
                move = true;
            } else return;
        }
        node = car2.page_position(e, moveP, $self);
        car2.page_translate(node);
        car2._handleEvent('move');
    },
    page_position: function (e, moveP, $self) {
        var now, next;
        if (moveP != 'undefined')car2._touchDeltaY = moveP - car2._touchStartValY;
        car2._movePosition = moveP - car2._touchStartValY > 0 ? 'down' : 'up';
        if (car2._movePosition != car2._movePosition_c) {
            car2._moveFirst = true;
            car2._movePosition_c = car2._movePosition;
        } else {
            car2._moveFirst = false;
        }
        if (car2._touchDeltaY <= 0) {
            if ($self.next('.m-page').length == 0) {
               return false; //最后一页禁止滑动到第一页
                car2._pageNext = 0;
            } else {
                car2._pageNext = car2._pageNow + 1;
            }
            next = car2._page.eq(car2._pageNext)[0];
        } else {
            if ($self.prev('.m-page').length == 0) {
                if (car2._firstChange) {
                    car2._pageNext = car2._pageNum - 1;
                } else {
                    return;
                }
            } else {
                car2._pageNext = car2._pageNow - 1;
            }
            next = car2._page.eq(car2._pageNext)[0];
        }
        now = car2._page.eq(car2._pageNow)[0];
        node = [next, now];
        if (car2._moveFirst)init_next(node);
        function init_next(node) {
            var s, l, _translateZ = car2._translateZ();
            car2._page.removeClass('action');
            $(node[1]).addClass('action').removeClass('f-hide');
            car2._page.not('.action').addClass('f-hide');
            car2.height_auto(car2._page.eq(car2._pageNext), 'false');
            $(node[0]).removeClass('f-hide').addClass('active');
            if (car2._movePosition == 'up') {
                s = parseInt($(window).scrollTop());
                if (s > 0)l = $(window).height() + s; else l = $(window).height();
                node[0].style[car2._prefixStyle('transform')] = 'translate(0,' + l + 'px)' + _translateZ;
                $(node[0]).attr('data-translate', l);
                $(node[1]).attr('data-translate', 0);
            } else {
                node[0].style[car2._prefixStyle('transform')] = 'translate(0,-' + Math.max($(window).height(), $(node[0]).height()) + 'px)' + _translateZ;
                $(node[0]).attr('data-translate', -Math.max($(window).height(), $(node[0]).height()));
                $(node[1]).attr('data-translate', 0);
            }
        }

        return node;
    },
    page_translate: function (node) {
        if (!node)return;
        var _translateZ = car2._translateZ(), y_1, y_2, scale, y = car2._touchDeltaY;
        if ($(node[0]).attr('data-translate'))y_1 = y + parseInt($(node[0]).attr('data-translate'));
        node[0].style[car2._prefixStyle('transform')] = 'translate(0,' + y_1 + 'px)' + _translateZ;
        if ($(node[1]).attr('data-translate'))y_2 = y + parseInt($(node[1]).attr('data-translate'));
        scale = 1 - Math.abs(y * 0.2 / $(window).height());
        y_2 = y_2 / 5;
        node[1].style[car2._prefixStyle('transform')] = 'translate(0,' + y_2 + 'px)' + _translateZ + ' scale(' + scale + ')';
    },
    page_touch_end: function (e) {
        car2._moveInit = false;
        car2._mouseDown = false;
        if (!car2._moveStart)return;
        if (!car2._pageNext && car2._pageNext != 0)return;
        car2._moveStart = false;
        if (Math.abs(car2._touchDeltaY) > 10) {
            car2._page.eq(car2._pageNext)[0].style[car2._prefixStyle('transition')] = 'all .3s';
            car2._page.eq(car2._pageNow)[0].style[car2._prefixStyle('transition')] = 'all .3s';
        }
        if (Math.abs(car2._touchDeltaY) >= 100) {
            car2.page_success();
        } else if (Math.abs(car2._touchDeltaY) > 10 && Math.abs(car2._touchDeltaY) < 100) {
            car2.page_fial();
        } else {
            car2.page_fial();
        }
        car2._handleEvent('end');
        car2._movePosition = null;
        car2._movePosition_c = null;
        car2._touchStartValY = 0;
    },
    page_success: function () {
        var _translateZ = car2._translateZ();
        car2._page.eq(car2._pageNext)[0].style[car2._prefixStyle('transform')] = 'translate(0,0)' + _translateZ;
        var y = car2._touchDeltaY > 0 ? $(window).height() / 5 : -$(window).height() / 5;
        var scale = 0.8;
        car2._page.eq(car2._pageNow)[0].style[car2._prefixStyle('transform')] = 'translate(0,' + y + 'px)' + _translateZ + ' scale(' + scale + ')';
        car2._handleEvent('success');
    },
    page_fial: function () {
        var _translateZ = car2._translateZ();
        if (!car2._pageNext && car2._pageNext != 0) {
            car2._moveStart = true;
            car2._moveFirst = true;
            return;
        }
        if (car2._movePosition == 'up') {
            car2._page.eq(car2._pageNext)[0].style[car2._prefixStyle('transform')] = 'translate(0,' + $(window).height() + 'px)' + _translateZ;
        } else {
            car2._page.eq(car2._pageNext)[0].style[car2._prefixStyle('transform')] = 'translate(0,-' + $(window).height() + 'px)' + _translateZ;
        }
        car2._page.eq(car2._pageNow)[0].style[car2._prefixStyle('transform')] = 'translate(0,0)' + _translateZ + ' scale(1)';
        car2._handleEvent('fial');
    },
    haddle_envent_fn: function () {
        car2._on('start', car2.lazy_bigP);
        car2._on('fial', function () {
            setTimeout(function () {
                car2._page.eq(car2._pageNow).attr('data-translate', '');
                car2._page.eq(car2._pageNow)[0].style[car2._prefixStyle('transform')] = '';
                car2._page.eq(car2._pageNow)[0].style[car2._prefixStyle('transition')] = '';
                car2._page.eq(car2._pageNext)[0].style[car2._prefixStyle('transform')] = '';
                car2._page.eq(car2._pageNext)[0].style[car2._prefixStyle('transition')] = '';
                car2._page.eq(car2._pageNext).removeClass('active').addClass('f-hide');
                car2._moveStart = true;
                car2._moveFirst = true;
                car2._pageNext = null;
                car2._touchDeltaY = 0;
                car2._page.eq(car2._pageNow).attr('style', '');
            }, 300)
        })
        car2._on('success', function () {
            if (car2._pageNext == 0 && car2._pageNow == car2._pageNum - 1) {
                car2._firstChange = true;
            }
            setTimeout(function () {
                if (car2._pageNext == car2._pageNum - 1)$('.u-arrow').addClass('f-hide'); else $('.u-arrow').removeClass('f-hide');
                car2._page.eq(car2._pageNow).addClass('f-hide');
                car2._page.eq(car2._pageNow).attr('data-translate', '');
                car2._page.eq(car2._pageNow)[0].style[car2._prefixStyle('transform')] = '';
                car2._page.eq(car2._pageNow)[0].style[car2._prefixStyle('transition')] = '';
                car2._page.eq(car2._pageNext)[0].style[car2._prefixStyle('transform')] = '';
                car2._page.eq(car2._pageNext)[0].style[car2._prefixStyle('transition')] = '';
                $('.p-ct').removeClass('fixed');
                car2._page.eq(car2._pageNext).removeClass('active');
                car2._page.eq(car2._pageNext).removeClass('fixed');
                car2._pageNow = car2._pageNext;
                car2._moveStart = true;
                car2._moveFirst = true;
                car2._pageNext = null;
                car2._page.eq(car2._pageNow).attr('style', '');
                car2._page.eq(car2._pageNow).removeClass('fixed');
                car2._page.eq(car2._pageNow).attr('data-translate', '');
                car2._touchDeltaY = 0;
                setTimeout(function () {
                    if (car2._page.eq(car2._pageNow).hasClass('z-animate'))return;
                    car2._page.eq(car2._pageNow).addClass('z-animate');
                }, 10)
                $('.j-detail').removeClass('z-show');
                $('.txt-arrow').removeClass('z-toggle');
            }, 300);
        })
    },
    audio_init: function () {
        var options_audio = {loop: true, preload: "auto", src: car2._audioNode.attr('data-src')}
        //var options_audio = {loop: true, preload: "auto", src:''}
        car2._audio = new Audio();
        for (var key in options_audio) {
            if (options_audio.hasOwnProperty(key) && (key in car2._audio)) {
                car2._audio[key] = options_audio[key];
            }
        }
        car2._audio.load();
    },
    audio_addEvent: function () {
        if (car2._audioNode.length <= 0)return;
        var txt = car2._audioNode.find('.txt_audio'), time_txt = null;
        car2._audioNode.find('.btn_audio').on('click', car2.audio_contorl);
        $(car2._audio).on('play', function () {
            car2._audio_val = false;
            audio_txt(txt, true, time_txt);
            $.fn.coffee.start();
            $('.coffee-steam-box').show(500);
        })
        $(car2._audio).on('pause', function () {
            audio_txt(txt, false, time_txt)
            $.fn.coffee.stop();
            $('.coffee-steam-box').hide(500);
        })
        function audio_txt(txt, val, time_txt) {
            if (val)txt.text('打开'); else txt.text('关闭');
            if (time_txt)clearTimeout(time_txt);
            txt.removeClass('z-move z-hide');
            time_txt = setTimeout(function () {
                txt.addClass('z-move').addClass('z-hide');
            }, 1000)
        }
    },
    audio_contorl: function () {
        if (!car2._audio_val) {
            car2.audio_stop();
        } else {
            car2.audio_play();
        }
    },
    audio_play: function () {
        car2._audio_val = false;
        if (car2._audio)car2._audio.play();
    },
    audio_stop: function () {
        car2._audio_val = true;
        if (car2._audio)car2._audio.pause();
    },
    video_init: function () {
        $('.j-video').each(function () {
            var option_video = {
                controls: 'controls',
                preload: 'none',
                width: $(this).attr('data-width'),
                height: $(this).attr('data-height'),
                src: $(this).attr('data-src')
            }
            var video = $('<video class="f-hide"></video>')[0];
            for (var key in option_video) {
                if (option_video.hasOwnProperty(key) && (key in video)) {
                    video[key] = option_video[key];
                }
                this.appendChild(video);
            }
            var img = $(video).prev();
            $(video).on('play', function () {
                img.hide();
                $(video).removeClass('f-hide');
            });
            $(video).on('pause', function () {
                img.show();
                $(video).addClass('f-hide');
            });
        })
        $('.j-video .img').on('click', function () {
            var video = $(this).next()[0];
            if (video.paused) {
                $(video).removeClass('f-hide');
                video.play();
                $(this).hide();
            }
        })
    },
    media_control: function () {
        if (!car2._audio)return;
        if ($('video').length <= 0)return;
        $(car2._audio).on('play', function () {
            $('video').each(function () {
                if (!this.paused) {
                    this.pause();
                }
            });
        });
        $('video').on('play', function () {
            if (!car2._audio_val) {
                car2.audio_contorl();
            }
        });
    },
    media_init: function () {
        car2.audio_init();
        car2.video_init();
        car2.audio_addEvent();
        car2.media_control();
    },
    lazy_img: function () {
        var lazyNode = $('.lazy-img');
        lazyNode.each(function () {
            var self = $(this);
            if (self.is('img')) {
                self.attr('src', 'res/img/load.gif');
            } else {
                var position = self.css('background-position'), size = self.css('background-size');
                self.attr({'data-position': position, 'data-size': '100% 100%'});
                if (self.attr('data-bg') == 'no') {
                    self.css({'background-repeat': 'no-repeat'})
                }
                self.css({
                    'background-image': 'url(res/img/load.gif)',
                    'background-size': '32px 32px',
                    'background-position': 'center'
                })
                if (self.attr('data-image') == 'no') {
                    self.css({'background-image': 'none'})
                }
            }
        })
    },
    lazy_start: function () {
        setTimeout(function () {
            for (var i = 0; i < 3; i++) {
                var node = $(".m-page").eq(i);
                if (node.length == 0)break;
                if (node.find('.lazy-img').length != 0) {
                    car2.lazy_change(node, false);
                    if (node.attr('data-page-type') == 'flyCon') {
                        car2.lazy_change($('.m-flypop'), false);
                    }
                } else continue;
            }
        }, 200)
    },
    lazy_bigP: function () {
        for (var i = 3; i <= 5; i++) {
            var node = $(".m-page").eq(car2._pageNow + i);
            if (node.length == 0)break;
            if (node.find('.lazy-img').length != 0) {
                car2.lazy_change(node, false);
                if (node.attr('data-page-type') == 'flyCon') {
                    car2.lazy_change($('.m-flypop'), false);
                }
            } else continue;
        }
    },
    lazy_change: function (node, goon) {
        if (node.attr('data-page-type') == '3d')car2.lazy_3d(node);
        if (node.attr('data-page-type') == 'flyCon') {
            var img = $('.m-flypop').find('.lazy-img');
            img.each(function () {
                var self = $(this), srcImg = self.attr('data-src');
                $('<img />').on('load', function () {
                    if (self.is('img')) {
                        self.attr('src', srcImg)
                    }
                }).attr("src", srcImg);
            })
        }
        var lazy = node.find('.lazy-img');
        lazy.each(function () {
            var self = $(this), srcImg = self.attr('data-src'), position = self.attr('data-position'), size = self.attr('data-size');
            if (self.attr('data-bg') != 'no') {
                $('<img />').on('load', function () {
                    if (self.is('img')) {
                        self.attr('src', srcImg)
                    } else {
                        self.css({
                            'background-image': 'url(' + srcImg + ')',
                            'background-position': 'center 30%',
                            'background-size': 'cover',
                            'background-repeat': 'no-repeat'
                        })
                    }
                    if (goon) {
                        for (var i = 0; i < $(".m-page").size(); i++) {
                            var page = $(".m-page").eq(i);
                            if ($(".m-page").find('.lazy-img').length == 0)continue
                            else {
                                car2.lazy_change(page, true);
                            }
                        }
                    }
                }).attr("src", srcImg);
                self.removeClass('lazy-img').addClass('lazy-finish');
            } else {
                if (self.attr('data-auto') == 'yes')self.css('background', 'none');
            }
        })
    },
    height_auto: function (ele, val) {
        var height = $(window).height();
        ele.children('.page-con').css('height', height);
        var vial = true;
        if (!vial) {
            if (ele.height() <= height) {
                ele.children('.page-con').height(height);
                if ((!$('.p-ct').hasClass('fixed')) && val == 'true')$('.p-ct').addClass('fixed');
            } else {
                if (val == 'true')$('.p-ct').removeClass('fixed');
                ele.children('.page-con').css('height', '100%');
                return;
            }
        } else {
            ele.children('.page-con').height(height);
            if ((!$('.p-ct').hasClass('fixed')) && val == 'true')$('.p-ct').addClass('fixed');
        }
    },
    loadingPageShow: function () {
        $('.u-pageLoading').show();
    },
    loadingPageHide: function () {
        $('.u-pageLoading').hide();
    },
    plugin: function () {
        $('#coffee_flow').coffee({
            steams: ["<img src='res/img/audio_widget_01@2x.png' />", "<img src='res/img/audio_widget_01@2x.png' />"],
            steamHeight: 100,
            steamWidth: 44
        });
        car2.start_callback();
    },
    start_callback: function () {
        car2.page_start();
        $('.u-arrow').removeClass('f-hide');
        if (!car2._audio)return;
        car2._audioNode.removeClass('f-hide');
        car2._audio.play();
        $(document).one("touchstart", function () {
            car2._audio.play();
        });
    },
    styleInit: function () {
        document.body.style.userSelect = 'none';
        document.body.style.mozUserSelect = 'none';
        document.body.style.webkitUserSelect = 'none';
        if (car2._IsPC())$(document.body).addClass('pc'); else $(document.body).addClass('mobile');
        if (car2._Android)$(document.body).addClass('android');
        if (car2._iPhoen)$(document.body).addClass('iphone');
        if (!car2._hasPerspective()) {
            car2._rotateNode.addClass('transformNode-2d');
            $(document.body).addClass('no-3d');
        }
        else {
            car2._rotateNode.addClass('transformNode-3d');
            $(document.body).addClass('perspective');
            $(document.body).addClass('yes-3d');
        }

        setTimeout(function () {
            $('.m-alert').find('strong').addClass('z-show');
        }, 1000)
        var h_ = $(window).height();
        $('.p-ct,.m-page,#j-mengban,.translate-back').height(h_);
    },
    init: function () {

        this.styleInit();
        this.haddle_envent_fn();
        $('<img />').attr('src', $('#r-cover').val());
        $('<img />').attr('src', $('.m-fengye').find('.page-con').attr('data-src'));
        var loading_time = new Date().getTime();
        $(window).on('load', function () {

            var now = new Date().getTime();
            var loading_end = false;
            var time;
            var time_del = now - loading_time;
            if (time_del >= 500) {
                loading_end = true;
            }
            if (loading_end) {
                time = 0;
            } else {
                time = 500 - time_del;
            }

            setTimeout(function () {
                setTimeout(function () {
                    $('.m-alert').addClass('f-hide');
                }, 1000)
                $('#j-mengban').addClass('z-show');
                setTimeout(function () {
                    $('.translate-back').removeClass('f-hide');
                    $('.m-fengye').removeClass('f-hide');
                    car2.height_auto(car2._page.eq(car2._pageNow), 'false');
                }, 1000)
                car2.media_init();
                car2.lazy_start();
                car2.plugin();


                var channel_id = location.search.substr(location.search.indexOf("channel=") + 8);
                channel_id = channel_id.match(/^\d+/);
                if (!channel_id || isNaN(channel_id) || channel_id < 0) {
                    channel_id = 1;
                }
                var activity_id = $('#activity_id').val();
                var h_ = $(window).height();
                $('.p-ct,.m-page,#j-mengban,.translate-back').height(h_);
            }, time)
        })



    }
};
car2.init();