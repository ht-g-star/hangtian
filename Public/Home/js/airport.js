$(".ch_tk_btn").on("click",function(){
        $(this).parent().next(".hbdt_group").toggle()
    });
    //点击展示城市选择框
    $("#startcity,#endcity").on("focus", function() {
        showIpt = $(this); //获取获得焦点元素
        var sTop = $(this).offset().top; //定位y
        var sLeft = $(this).offset().left; //定位x
        if ($("input[name='tk_type']:checked").val() == "ch") { //国内出行
            $("#city_ch").css("top", sTop + 36 + 'px').css("left", sLeft + 'px').show();//定位展示
            $("#city_in").hide();			//隐藏国际城市选择
            $("#city_ch .port_list a[data-hot != 'true']").hide();		//默认显示国内热门城市

            $(".port_index").hide();
            $(".port_list a").hide();
            $(".port_list a[data-hot='true']").show();

            $(".port_tabbar a").removeClass("active");
            $(".port_tabbar a:first-child").addClass('active');

            $(".port_list a").on("click", function() {	//某个城市被选中后事件绑定
                showIpt.val($(this).html());
                $(".ariport_box").hide();
            });
         } else { //国际出行
            $.getJSON("/Public/js/Iairport.json", function(data) {
                var cityhtml = "";
                //生产html
                for (i = 0; i < data.intal_port.length; i++) {
                    cityhtml = cityhtml + '<a data-id="' + data.intal_port[i].port_id +
                            '" data-sp="' + data.intal_port[i].port_spell +
                            '" data-qy="' + data.intal_port[i].QCellCore +
                            '" data-hot="' + data.intal_port[i].is_hot +
                            '">' + data.intal_port[i].show_name + '</a>';
                }
                $("#city_in .port_list").html(cityhtml); //加入dom
                $("#city_in .port_list a[data-hot!='true']").hide(); //初始显示热门城市
                $(".port_list a").on("click", function() { //某个城市被选中后事件绑定
                    showIpt.val($(this).html());
                    $(".ariport_box").hide();
                });
                $("#city_in").css("top", sTop + 36 + 'px').css("left", sLeft + 'px').show(); //定位
                $("#city_ch").hide(); //隐藏国内城市选择
            });
        }
    });


    $('#btnSubmit').on('click', function(){
        var chufa = $('#startcity').val(); //出发城市
        var end = $('#endcity').val(); //落地城市
        var startdate = $('#startdate').val(); //出发时间
        /*var backdate = $('#backdate').val(); //到达时间*/
        if(!chufa.length){
            alert('请选择出发城市！');
            return;
        }else if(!end.length){
            alert('请选择到达城市!');
            return;
        }else if(chufa == end){
            alert('出发和到达城市不能相同!');
            return;
        }else if(!startdate.length){
            alert('请选择出发时间!');
            return;
        }else{
            location.href='{:U("select")}&chufa='+chufa+'&end='+end+'&startdate='+startdate;
        }

    });


    var showIpt;
    //切换国内国际
    $(".ticket_tab_bar li").on("click", function() {
        $(".ticket_tab_bar li").removeClass("active");
        $(this).addClass("active");
    });
    //热门城市 与全部 切换
    $("#city_ch .port_tabbar a").on("click", function() {
        $(".port_tabbar a").removeClass("active");
        $(this).addClass("active");
        if ($(this).attr("href") == "#hot") { //点击热门城市
            $(".port_index").hide();
            $(".port_list a").hide();
            $(".port_list a[data-hot='true']").show();
        } else { //点击全部城市
            $(".port_index").show();
            $(".port_index span").removeClass("active");
            $(".port_index span:first").addClass("active");
            $(".port_list a").hide();
            //$(".port_list a[data-hot='false']").show();
            eachCity('A', 'E'); //默认展示A-E
        }
    });
    //单、返程选择
    $("input[type='radio'][name='hbtype']").on('click', function() {
        if ($(this).val() == "wf") {
            $("#backdate").removeAttr("disabled");
        } else {
            $("#backdate").attr("disabled", "disabled");
        }
    });
    //国内城市 索引点击
    $(".port_index span").on("click", function() {
        $(".port_list a").hide();
        $(".port_index span").removeClass("active");
        $(this).addClass("active");
        var fistW = $(this).html().substring(0, 1);
        var lastW = $(this).html().substr($(this).html().length - 1);
        eachCity(fistW, lastW);
    });
    //显示城市某区间内的城市
    function eachCity(f, l) {
        $(".port_list a").each(function() {
            var myW = $(this).attr("data-sp").substring(0, 1); //获取首字母
            if (myW >= f && myW <= l) {
                $(this).show();
            }
        });
    }
    //国际城市
    $("#city_in .port_tabbar a").on("click", function() {
        $("#city_in .port_tabbar a").removeClass("active");
        $(this).addClass("active");
        var thisqy = $(this).attr("href").replace("#", ""); //获取选择区域
        if (thisqy == "hot") {
            $("#city_in .port_list a").hide();
            $("#city_in .port_list a[data-hot='true']").show();
        } else {
            $("#city_in .port_list a").hide();
            $('#city_in .port_list a[data-qy=\'' + thisqy + '\']').show();
        }
    });
    $(".xz_ts a").on("click", function() {
        $(".ariport_box").hide();
    });
    //日期选择框初始化
    $.datetimepicker.setLocale('ch');
    $("#startdate,#backdate").datetimepicker({
        timepicker: false,
        format: 'Y-m-d ',
        minDate: '-1970/01/01'
    });
    //交换城市
    function ExchangeCtiy() {
        var nowScity = $("#startcity").val();
        var nowEcity = $("#endcity").val();
        $("#startcity").val(nowEcity);
        $("#endcity").val(nowScity);
    }