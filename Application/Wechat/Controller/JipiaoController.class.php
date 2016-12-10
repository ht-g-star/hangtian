<?php
namespace Wechat\Controller;

use Org\Wechat\TPWechat;
use Org\Wechat\Wechat;
use Think\Controller;
use Think\Log;

class JipiaoController extends FollowerController {


    /**
     * 首页
     */
    public function index(){
    $m = M('jipiao_chengshi');
    $city = $m->order('remen desc')->select();
    $this->assign('city1',$city);
    $this->assign('city2',$city);
        $this->display();
    }

    /**
     * 航班查询接口
     */
    public function select(){
        header("Content-type: text/html; charset=utf-8");
        $Filght =  new \Api\Controller\FlightController();
        $data = array(
            'fromCity'=>I('get.chufa'),
            'toCity'=>I('get.end'),
            'fromDate'=>I('get.startdate'),
            'backDate'=>I('get.enddate')
        );
        if(I('iswf')=='1'){
          $cache = $Filght->bpwfselect($data);
          //根据航空公司对航班进行航班配对
          $sarr=$cache[0];
          $earr=$cache[1];
          $list=$cache;
          // unset($list[1]['flightObject']);
          // foreach ($sarr['flightObject'] as $k => $v) {
          //   $scom=substr($v['flightNo'], 0,2);
          //   foreach ($earr['flightObject'] as $kk => $vv) {
          //     $ecom=substr($vv['flightNo'], 0,2);
          //     if($scom==$ecom){
          //       $list[1]['flightObject'][$k]=$vv;
          //     }
          //   }
          // }
        }else{
            $list = $Filght->bpSelect($data);
        }

        unset($_SESSION['hblist']);
        $_SESSION['hblist']=$list;
        $this->assign('list', $list);     
        $this->display();
    }


    /**
     * 线路详情
     */
    public function show(){
        header("Content-type: text/html; charset=utf-8");

        $data = array(
            'chufa'=>I('get.fromCity'),
            'end'=>I('get.toCity'),
            'fromDate'=>I('get.fromDate'),
            "kf"=> I("get.kf"),
            "time"=>I("get.time")
        );

        $info = session('flightObject_'.$data["time"]."_".$data["kf"]);
        Log::write("机票缓存：".json_encode($info));

        if(empty($data['chufa']) || empty($data['end']) || empty($data['fromDate'])){
            $this->redirect('index');
            exit;
        }
        if(empty($info)){
            $this->redirect('select', $data);
            exit;
        }

        $info['fromDate_h'] = date('H:i', strtotime($info['fromDate']));
        $info['toDate_h'] = date('H:i', strtotime($info['toDate']));
        $info['fromDate'] = date('m-d', strtotime($info['fromDate']));
        $info['toDate'] = date('m-d', strtotime($info['toDate']));

        $this->assign('info', $info);
        $this->display();
    }

    public function addOrder() {
        header("Content-type: text/html; charset=utf-8");
        if (!is_login()) {
            $this->redirect("User/login");
            exit;
        }

        $uid = is_login();
        if ($_POST) {
            $uid = is_login();
            $Filght = new \Api\Controller\FlightController();
            $realid = date('Ym', time()) . time() . md5(date('Ym', time()) . time()) . is_login();
            $orderid = "0" . date('Ym', time()) . time() . is_login();
            $orderid1 = "1" . date('Ym', time()) . time() . is_login();
            $k = $_GET['o'];
            $kk = $_GET['o1'];
            $g = I('go') - 1;
            $f = I('back') - 1;


            $isover18 = I("post.isover18");
            $toubaoren = array(
                "name" => I("post.toubaoren"),
                "phone" => I("post.toubaorenphone"),
                "id" => I("post.toubaorenid")
              );

            //乘机人信息
            $lxr = M('address')->where(array(
                'id' => I('lxr')
            ))->find();

            $ckid = implode(",", $_POST["cjr"]);
            $ckmap["id"] = array("IN", $ckid);
            $ck = M('cjr')->where($ckmap)->select();
            $ckcount = count($ck);
            

            //去程请求
            if (count($k) > 0) {
                $data = array(
                    'externalOrderNo' => $orderid, //外部订单号   String  是 采购商订单号
                    'policyId' => $_SESSION['hblist'][0]['flightObject'][$k]['cabinInfo'][$g]['policy'][0]['policyId'],
                    'boundPrice' => 'false', //是否打包运价  boolean 是 该参数当前无作用
                    'facePrice' => $_SESSION['hblist'][0]['flightObject'][$k]['cabinInfo'][$g]['facePrice'], //票面价 int 是 是指单张成人的票面价，当航程类型是往返时，票面价为往返单张成人票面价之和，不含燃油机建
                    'totalOrderPrice' => $_SESSION['hblist'][0]['flightObject'][$k]['cabinInfo'][$g]['facePrice'] * $ckcount, //票面总价 int 是 不含燃油机建，所有乘客票面价的总和，票面总价 = 成人数 * 单张成人票面价 + 儿童数 * 单张儿童票面价 + 婴儿数 * 单张婴儿票面价
                    'orderSettlePrice' => $_SESSION['hblist'][0]['flightObject'][$k]['cabinInfo'][$g]['policy'][0]["settlement"] * $ckcount, //订单结算总价 double  是 不含燃油机建，所有乘客结算价的总和，如：800.23
                    'flightRangeType' => 'OW', //航程类型 FlightRangeTypeEnum 是 OW-单程|RT-往返|OJ-联程
                    // 'flightRangeType'=>count($_SESSION['hblist'])==1?'OW':'RT',//航程类型 FlightRangeTypeEnum 是 OW-单程|RT-往返|OJ-联程
                    'afterTicketUrl' => U('Home/Jipiao/chupiao', array(
                        'externalOrderNo' => $orderid
                    ) , 'html') , //出票后通知URL
                    'afterPayUrl' => U('Home/Jipiao/setPay', array(
                        'externalOrderNo' => $orderid
                    ) , 'html') , //支付后通知URL  String  是 通过支付宝、账户支付等第三方网上支付方式需要给予通知URL，通过URL方式传递数据，支付信息：1、isSuccess-支付状态（true-支付成功|false-支付失败） 2、orderId-订单号 3、externalId-外部订单号 4、payType-支付方式 5、traceNum -交易流水号 6、amount-金额（单位：分） 7、orderStatus-订单状态 8、reason-失败原因。
                    'afterRefundTicketUrl' => U('Home/Jipiao/refundnd', array(
                        'externalOrderNo' => $orderid
                    ) , 'html') , //退票通知URL String  是 通过URL方式传递数据，退票成功：1、isSuccess-退票状态 2、originalOrderID-原订单号 3、refundOrderID-退票单号 4、externalOrderID-外部订单号 5、refundAmount-退款金额（单位：分） 6、poundage-手续费（单位： 分）；
                    // 'afterRefundUrl'=>U('Home/Jipiao/refundnd', array('externalOrderNo'=>$orderid), 'html'),//退款通知URL String  否 通过URL方式传递数据(已经废弃)
                    'flightNo' => $_SESSION['hblist'][0]['flightObject'][$k]['flightNo'], //航班号 String  是 CA1858
                    'cabinCode' => $_SESSION['hblist'][0]['flightObject'][$k]['cabinInfo'][$g]['cabinCode'], //舱位  String  是
                    'cabinType' => $_SESSION['hblist'][0]['flightObject'][$k]['cabinInfo'][$g]['cabinRank'], //舱位等级  String  是 Y-经济舱|C-商务舱|F-头等舱
                    'fromAirport' => $_SESSION['hblist'][0]['flightObject'][$k]['fromAirport'], //出发机场 String  是 机场三字码，如：SHA
                    'toAirport' => $_SESSION['hblist'][0]['flightObject'][$k]['toAirport'], //到达机场 String  是 机场三字码，如：PEK
                    'fromDate' => $_SESSION['hblist'][0]['flightObject'][$k]['fromDate'], // X 出发时间  dateTime  是 格式：2013-12-31T07:00:00.000+08:00
                    'toDate' => $_SESSION['hblist'][0]['flightObject'][$k]['toDate'], // x 到达时间 dateTime  是 格式：2013-12-31T09:20:00.000+08:00
                    'fromTower' => $_SESSION['hblist'][0]['flightObject'][$k]['fromTower'], //出发航站楼 String  否 T2
                    'planeModle' => $_SESSION['hblist'][0]['flightObject'][$k]['planeModel'], // x机型 String  是 77S
                    'airportFee' => $_SESSION['hblist'][0]['flightObject'][$k]['airportFee'], // x机场建设税  int 是  
                    'fuelTax' => $_SESSION['hblist'][0]['flightObject'][$k]['fuelTax'], //x 燃油费  int 是  
                    // 'name' => $ck['cjrname'], //乘客姓名  String  是
                    // 'passengerType' => $ck['cjrfm'], //乘客类型  PassengerTypeEnum 是 ADU-成人|CHD-儿童|INF-儿童
                    'credentialType' => 'NI', //证件类型  CredentialTypeEnum  是 NI-身份证|PP-护照|ID-其他
                    // 'credentialCode' => $ck['cjrid'], //证件号码 String  是 如果为身份证，则验证身份证号码，验证规则：1、长度必须为18位，并且前17位必须是数字 2、利用乘客输入的生日信息验证身份证内的出入日期信息 3、前两位地区码必须是有效的地区码 d.根据前17位算出最后一位校验码，然后判断校验码是否正确
                    // 'gender' => (substr($ck['cjrid'], -2, 1) % 2 == 1) ? 'M' : 'F', //性别 PassengerGenderEnum 否 F-男|M-女,如果为公民身份证，会通过身份证获得性别；身份证号码倒数第二位如果是奇数则为男性，反之则为女性
                    // 'birthday' => msubstr($ck['cjrid'], 6, 4, 'utf-8', false) . '-' . msubstr($ck['cjrid'], 10, 2, 'utf-8', false) . '-' . msubstr($ck['cjrid'], 12, 2, 'utf-8', false) , //生日  date  是 利用生日判断乘客类型是否正确。2-12周岁为儿童，12周岁以上为成人，利用生日判断乘客类型是否正确。2-12周岁为儿童，12周岁以上为成人 
                    //msubstr($ck['cjrid'],6,4,'utf-8',false).'-'.msubstr($ck['cjrid'],10,2,'utf-8',false).'-'.msubstr($ck['cjrid'],12,2,'utf-8',false)
                    // 'phone' => $ck['cjrmobile'], //手机  String  是 成人手机号码必须有，儿童手机号码可以和成人一样，验证方法长度至少为1
                    'passengers'=>$ck,
                    'lxr_name' => $lxr['realname'], //联系人姓名 String  是  
                    'lxr_phone' => $lxr['cellphone'], //手机  String  是  
                    'deliveryType' => 'NODELIVERY', //配送方式  DeliveryTypeEnum  是 NODELIVERY-不需配送|POST_DELIVERY-邮寄（暂不支持）|SEND_TICKET-送票（暂不支持）|GETBYSELF-自取（暂不支持） 
                    'payType' => 'ACCOUNTPAY', //支付方式  PayTypeEnum 是 ACCOUNTPAY-账户支付|ALIPAY-支付宝|TENPAY-财付通|CHINAPNR-汇付|E_BANK-网银(暂不支持)|AIRPLUS-AirPlus|ALIAGENTPAY-支付宝代扣
                    'clientIP' => ''
                    //支付人IP String  是 财付通支付必须有
                    
                );
                $res = $Filght->bpAddOrder($data);
            }
            //返程请求
            if (count($kk) > 0) {
                $data1 = array(
                    'externalOrderNo' => $orderid1, //外部订单号   String  是 采购商订单号
                    'policyId' => $_SESSION['hblist'][1]['flightObject'][$kk]['cabinInfo'][$g]['policy'][0]['policyId'],
                    'boundPrice' => 'false', //是否打包运价  boolean 是 该参数当前无作用
                    'facePrice' => $_SESSION['hblist'][1]['flightObject'][$kk]['cabinInfo'][$f]['facePrice'], //票面价 int 是 是指单张成人的票面价，当航程类型是往返时，票面价为往返单张成人票面价之和，不含燃油机建
                    'totalOrderPrice' => $_SESSION['hblist'][1]['flightObject'][$kk]['cabinInfo'][$f]['facePrice'] * $ckcount, //票面总价 int 是 不含燃油机建，所有乘客票面价的总和，票面总价 = 成人数 * 单张成人票面价 + 儿童数 * 单张儿童票面价 + 婴儿数 * 单张婴儿票面价
                    'orderSettlePrice' => $_SESSION['hblist'][1]['flightObject'][$kk]['cabinInfo'][$f]['policy'][0]["settlement"] * $ckcount, //订单结算总价 double  是 不含燃油机建，所有乘客结算价的总和，如：800.23
                    'flightRangeType' => 'OW', //航程类型 FlightRangeTypeEnum 是 OW-单程|RT-往返|OJ-联程
                    // 'flightRangeType'=>count($_SESSION['hblist'])==1?'OW':'RT',//航程类型 FlightRangeTypeEnum 是 OW-单程|RT-往返|OJ-联程
                    'afterTicketUrl' => U('Home/Jipiao/chupiao', array(
                        'externalOrderNo' => $orderid1
                    ) , 'html') , //出票后通知URL
                    'afterPayUrl' => U('Home/Jipiao/setPay', array(
                        'externalOrderNo' => $orderid1
                    ) , 'html') , //支付后通知URL  String  是 通过支付宝、账户支付等第三方网上支付方式需要给予通知URL，通过URL方式传递数据，支付信息：1、isSuccess-支付状态（true-支付成功|false-支付失败） 2、orderId-订单号 3、externalId-外部订单号 4、payType-支付方式 5、traceNum -交易流水号 6、amount-金额（单位：分） 7、orderStatus-订单状态 8、reason-失败原因。
                    'afterRefundTicketUrl' => U('Home/Jipiao/refundnd', array(
                        'externalOrderNo' => $orderid1
                    ) , 'html') , //退票通知URL String  是 通过URL方式传递数据，退票成功：1、isSuccess-退票状态 2、originalOrderID-原订单号 3、refundOrderID-退票单号 4、externalOrderID-外部订单号 5、refundAmount-退款金额（单位：分） 6、poundage-手续费（单位： 分）；
                    // 'afterRefundUrl'=>U('Home/Jipiao/refundnd', array('externalOrderNo'=>$orderid), 'html'),//退款通知URL String  否 通过URL方式传递数据(已经废弃)
                    'flightNo' => $_SESSION['hblist'][1]['flightObject'][$kk]['flightNo'], //航班号 String  是 CA1858
                    'cabinCode' => $_SESSION['hblist'][1]['flightObject'][$kk]['cabinInfo'][$f]['cabinCode'], //舱位  String  是
                    'cabinType' => $_SESSION['hblist'][1]['flightObject'][$kk]['cabinInfo'][$f]['cabinRank'], //舱位等级  String  是 Y-经济舱|C-商务舱|F-头等舱
                    'fromAirport' => $_SESSION['hblist'][1]['flightObject'][$kk]['fromAirport'], //出发机场 String  是 机场三字码，如：SHA
                    'toAirport' => $_SESSION['hblist'][1]['flightObject'][$kk]['toAirport'], //到达机场 String  是 机场三字码，如：PEK
                    'fromDate' => $_SESSION['hblist'][1]['flightObject'][$kk]['fromDate'], // X 出发时间  dateTime  是 格式：2013-12-31T07:00:00.000+08:00
                    'toDate' => $_SESSION['hblist'][1]['flightObject'][$kk]['toDate'], // x 到达时间 dateTime  是 格式：2013-12-31T09:20:00.000+08:00
                    'fromTower' => $_SESSION['hblist'][1]['flightObject'][$kk]['fromTower'], //出发航站楼 String  否 T2
                    'planeModle' => $_SESSION['hblist'][1]['flightObject'][$kk]['planeModel'], // x机型 String  是 77S
                    'airportFee' => $_SESSION['hblist'][1]['flightObject'][$kk]['airportFee'], // x机场建设税  int 是  
                    'fuelTax' => $_SESSION['hblist'][1]['flightObject'][$kk]['fuelTax'], //x 燃油费  int 是  
                    // 'name' => $ck['cjrname'], //乘客姓名  String  是
                    // 'passengerType' => $ck['cjrfm'], //乘客类型  PassengerTypeEnum 是 ADU-成人|CHD-儿童|INF-儿童
                    'credentialType' => 'NI', //证件类型  CredentialTypeEnum  是 NI-身份证|PP-护照|ID-其他
                    // 'credentialCode' => $ck['cjrid'], //证件号码 String  是 如果为身份证，则验证身份证号码，验证规则：1、长度必须为18位，并且前17位必须是数字 2、利用乘客输入的生日信息验证身份证内的出入日期信息 3、前两位地区码必须是有效的地区码 d.根据前17位算出最后一位校验码，然后判断校验码是否正确
                    // 'gender' => (substr($ck['cjrid'], -2, 1) % 2 == 1) ? 'M' : 'F', //性别 PassengerGenderEnum 否 F-男|M-女,如果为公民身份证，会通过身份证获得性别；身份证号码倒数第二位如果是奇数则为男性，反之则为女性
                    // 'birthday' => msubstr($ck['cjrid'], 6, 4, 'utf-8', false) . '-' . msubstr($ck['cjrid'], 10, 2, 'utf-8', false) . '-' . msubstr($ck['cjrid'], 12, 2, 'utf-8', false) , //生日  date  是 利用生日判断乘客类型是否正确。2-12周岁为儿童，12周岁以上为成人，利用生日判断乘客类型是否正确。2-12周岁为儿童，12周岁以上为成人 
                    // //msubstr($ck['cjrid'],6,4,'utf-8',false).'-'.msubstr($ck['cjrid'],10,2,'utf-8',false).'-'.msubstr($ck['cjrid'],12,2,'utf-8',false)
                    // 'phone' => $ck['cjrmobile'], //手机  String  是 成人手机号码必须有，儿童手机号码可以和成人一样，验证方法长度至少为1
                    'passengers'=>$ck,
                    'lxr_name' => $lxr['realname'], //联系人姓名 String  是  
                    'lxr_phone' => $lxr['cellphone'], //手机  String  是  
                    'deliveryType' => 'NODELIVERY', //配送方式  DeliveryTypeEnum  是 NODELIVERY-不需配送|POST_DELIVERY-邮寄（暂不支持）|SEND_TICKET-送票（暂不支持）|GETBYSELF-自取（暂不支持） 
                    'payType' => 'ACCOUNTPAY', //支付方式  PayTypeEnum 是 ACCOUNTPAY-账户支付|ALIPAY-支付宝|TENPAY-财付通|CHINAPNR-汇付|E_BANK-网银(暂不支持)|AIRPLUS-AirPlus|ALIAGENTPAY-支付宝代扣
                    'clientIP' => ''
                    //支付人IP String  是 财付通支付必须有
                    
                );
                $res1 = $Filght->bpAddOrder($data1);
            }
            if (count($k) > 0 && count($kk) > 0) {
                //如果两张票都出票成功
                if (!empty($res['CreateOrderCommonResponse']) && !empty($res1['CreateOrderCommonResponse'])) {

                    for ($i = 0; $i < count($res['CreateOrderCommonResponse']['orderMoneyDetail']); $i++) { 
                        if($i != 0){
                          $passengername .= "|";
                          $credentialcode .= "|";
                          $passengertype .= "|";
                          $faceprice .= "|";
                          $settleprice .= "|";
                          $airportfee .= "|";
                          $fueltax .= "|";
                          $shengri .= "|";
                        }
                        $passengername .= $res['CreateOrderCommonResponse']['orderMoneyDetail'][$i]["passengerName"];
                        $credentialcode .= $res['CreateOrderCommonResponse']['orderMoneyDetail'][$i]["credentialCode"];
                        $passengertype .= $res['CreateOrderCommonResponse']['orderMoneyDetail'][$i]["passengerType"];
                        $faceprice .= $res['CreateOrderCommonResponse']['orderMoneyDetail'][$i]["facePrice"];
                        $settleprice .= $res['CreateOrderCommonResponse']['orderMoneyDetail'][$i]["settlePrice"];
                        $airportfee .= $res['CreateOrderCommonResponse']['orderMoneyDetail'][$i]['airportFee'];
                        $fueltax .= $res['CreateOrderCommonResponse']['orderMoneyDetail'][$i]['fuelTax'];
                        $shengri .= msubstr($res['CreateOrderCommonResponse']['orderMoneyDetail'][$i]['credentialCode'],6,4,'utf-8',false).'-'.msubstr($res['CreateOrderCommonResponse']['orderMoneyDetail'][$i]['credentialCode'],10,2,'utf-8',false).'-'.msubstr($res['CreateOrderCommonResponse']['orderMoneyDetail'][$i]['credentialCode'],12,2,'utf-8',false);
                        $adupnr = $res['CreateOrderCommonResponse']['orderMoneyDetail'][0]['pnrText'];
                      }

                      for ($i = 0; $i < count($res1['CreateOrderCommonResponse']['orderMoneyDetail']); $i++) { 
                        if($i != 0){
                          $passengername1 .= "|";
                          $credentialcode1 .= "|";
                          $passengertype1 .= "|";
                          $faceprice1 .= "|";
                          $settleprice1 .= "|";
                          $airportfee1 .= "|";
                          $fueltax1 .= "|";
                          $shengri1 .= "|";
                        }
                        $passengername1 .= $res1['CreateOrderCommonResponse']['orderMoneyDetail'][$i]["passengerName"];
                        $credentialcode1 .= $res1['CreateOrderCommonResponse']['orderMoneyDetail'][$i]["credentialCode"];
                        $passengertype1 .= $res1['CreateOrderCommonResponse']['orderMoneyDetail'][$i]["passengerType"];
                        $faceprice1 .= $res1['CreateOrderCommonResponse']['orderMoneyDetail'][$i]["facePrice"];
                        $settleprice1 .= $res1['CreateOrderCommonResponse']['orderMoneyDetail'][$i]["settlePrice"];
                        $airportfee1 .= $res1['CreateOrderCommonResponse']['orderMoneyDetail'][$i]['airportFee'];
                        $fueltax1 .= $res1['CreateOrderCommonResponse']['orderMoneyDetail'][$i]['fuelTax'];
                        $shengri1 .= msubstr($res1['CreateOrderCommonResponse']['orderMoneyDetail'][$i]['credentialCode'],6,4,'utf-8',false).'-'.msubstr($res1['CreateOrderCommonResponse']['orderMoneyDetail'][$i]['credentialCode'],10,2,'utf-8',false).'-'.msubstr($res1['CreateOrderCommonResponse']['orderMoneyDetail'][$i]['credentialCode'],12,2,'utf-8',false);
                        $adupnr1 = $res1['CreateOrderCommonResponse']['orderMoneyDetail'][0]['pnrText'];
                      }

                    $orderData = array(
                        'uid' => $uid,
                        'fromcitycn' => $_SESSION['hblist'][0]['fromCityCN'],
                        'tocitycn' => $_SESSION['hblist'][0]['toCityCN'],
                        'realid' => $realid,
                        'orderid' => $orderid, //'我方订单号',
                        'externalorderid' => $res['CreateOrderCommonResponse']['orderId'], //'接口订单号',
                        'orderstatus' => $res['CreateOrderCommonResponse']['orderStatus'], //'订单状态',
                        'totalorderprice'=>$res["CreateOrderCommonResponse"]["totalOrderPrice"] * 1.03, //'订单总价',
                        'ordersettleprice'=>$res['CreateOrderCommonResponse']['totalOrderPrice'], //'订单应付金额',
                        'passengername'=>$passengername, //'乘客姓名',
                        'credentialcode'=>$credentialcode, //'证件号码',
                        'passengertype'=>$passengertype, //'乘客类型',
                        'faceprice'=>$faceprice, //'票面价',
                        'settleprice'=>$settleprice, //'结算价',
                        'airportfee'=>$airportfee, //'机建',
                        'fueltax'=>$fueltax, //'燃油',
                        'lxr_name' => $data['lxr_name'], //'联系人姓名',
                        'lxr_phone' => $data['lxr_phone'], //'联系人手机号',
                        //msubstr($ck['cjrid'],6,4,'utf-8',false).'-'.msubstr($ck['cjrid'],10,2,'utf-8',false).'-'.msubstr($ck['cjrid'],12,2,'utf-8',false)
                        'shengri'=>$shengri, //'乘客出生日期',
                        'addtime' => time() , //'添加时间'
                        'flightno' => $_SESSION['hblist'][0]['flightObject'][$k]['flightNo'], //航班号
                        'cabinrankdetail' => $_SESSION['hblist'][0]['flightObject'][$k]['cabinInfo'][$g]['cabinRankDetail'], //舱位等级说明
                        'departuredate' => date('Y-m-d', strtotime($_SESSION['hblist'][0]['flightObject'][$k]['fromDate'])) , //机票日期
                        'departuretime' => date('H:i', strtotime($_SESSION['hblist'][0]['flightObject'][$k]['fromDate'])) , //出发时间
                        'arrivaldate' => date('Y-m-d', strtotime($_SESSION['hblist'][0]['flightObject'][$k]['toDate'])) , //抵达日期
                        'arrivaltime' => date('H:i', strtotime($_SESSION['hblist'][0]['flightObject'][$k]['toDate'])) , //抵达时间
                        'flightduration' => $_SESSION['hblist'][0]['flightObject'][$k]['flightDuration'], //时长
                        'fromairport' => $_SESSION['hblist'][0]['flightObject'][$k]['fromAirport'], //出发机场
                        'toairport' => $_SESSION['hblist'][0]['flightObject'][$k]['toAirport'], //目标机场
                        "fromtower" => $_SESSION['hblist'][0]['flightObject'][$k]['fromTower'],
                        "totower" => $_SESSION['hblist'][0]['flightObject'][$k]['toTower'],
                        'adupnr'=>$adupnr,
                        "toubaoren" => json_encode($toubaoren),
                        "rule" => json_encode($_SESSION["fromRule"])
                    );
                    $orderData1 = array(
                        'uid' => $uid,
                        'fromcitycn' => $_SESSION['hblist'][1]['fromCityCN'],
                        'tocitycn' => $_SESSION['hblist'][1]['toCityCN'],
                        'realid' => $realid,
                        'orderid' => $orderid1, //'我方订单号',
                        'externalorderid' => $res1['CreateOrderCommonResponse']['orderId'], //'接口订单号',
                        'orderstatus' => $res1['CreateOrderCommonResponse']['orderStatus'], //'订单状态',
                        'totalorderprice'=>$res1["CreateOrderCommonResponse"]["totalOrderPrice"] * 1.03, //'订单总价',
                        'ordersettleprice'=>$res1['CreateOrderCommonResponse']['totalOrderPrice'], //'订单应付金额',
                        'passengername'=>$passengername1, //'乘客姓名',
                        'credentialcode'=>$credentialcode1, //'证件号码',
                        'passengertype'=>$passengertype1, //'乘客类型',
                        'faceprice'=>$faceprice1, //'票面价',
                        'settleprice'=>$settleprice1, //'结算价',
                        'airportfee'=>$airportfee1, //'机建',
                        'fueltax'=>$fueltax1, //'燃油',
                        'lxr_name' => $data['lxr_name'], //'联系人姓名',
                        'lxr_phone' => $data['lxr_phone'], //'联系人手机号',
                        //msubstr($ck['cjrid'],6,4,'utf-8',false).'-'.msubstr($ck['cjrid'],10,2,'utf-8',false).'-'.msubstr($ck['cjrid'],12,2,'utf-8',false)
                        'shengri'=>$shengri1, //'乘客出生日期',
                        'addtime' => time() , //'添加时间'
                        'flightno' => $_SESSION['hblist'][1]['flightObject'][$kk]['flightNo'], //航班号
                        'cabinrankdetail' => $_SESSION['hblist'][1]['flightObject'][$kk]['cabinInfo'][$f]['cabinRankDetail'], //舱位等级说明
                        'departuredate' => date('Y-m-d', strtotime($_SESSION['hblist'][1]['flightObject'][$kk]['fromDate'])) , //机票日期
                        'departuretime' => date('H:i', strtotime($_SESSION['hblist'][1]['flightObject'][$kk]['fromDate'])) , //出发时间
                        'arrivaldate' => date('Y-m-d', strtotime($_SESSION['hblist'][1]['flightObject'][$kk]['toDate'])) , //抵达日期
                        'arrivaltime' => date('H:i', strtotime($_SESSION['hblist'][1]['flightObject'][$kk]['toDate'])) , //抵达时间
                        'flightduration' => $_SESSION['hblist'][1]['flightObject'][$kk]['flightDuration'], //时长
                        'fromairport' => $_SESSION['hblist'][1]['flightObject'][$kk]['fromAirport'], //出发机场
                        'toairport' => $_SESSION['hblist'][1]['flightObject'][$kk]['toAirport'], //目标机场
                        "fromtower" => $_SESSION['hblist'][1]['flightObject'][$kk]['fromTower'],
                        "totower" => $_SESSION['hblist'][1]['flightObject'][$kk]['toTower'],
                        'adupnr'=>$adupnr1,
                        "toubaoren" => json_encode($toubaoren),
                        "rule" => json_encode($_SESSION["toRule"])
                    );
                    $model = M("jipiao_order");
                    $model->add($orderData);
                    $model->add($orderData1);
                    
                    //添加保险
                    $insu = new \Api\Controller\InsuController();
                    if (!empty($_SESSION['insudata'])) {
                        $res = $insu->createOrderType2(
                            $_SESSION['insudata'][0]['prodId'], 
                            $orderid, 
                            $_SESSION['insudata'][0]['price'], 
                            U('Home/Jipiao/insurepaynd', 'html', true), 
                            U('Home/Jipiao/insureapplynd', 'html', true) , 
                            1,
                            $isover18,
                            $toubaoren);
                        $res1 = $insu->createOrderType2(
                            $_SESSION['insudata'][0]['prodId'], 
                            $orderid1, 
                            $_SESSION['insudata'][0]['price'], 
                            U('Home/Jipiao/insurepaynd', 'html', true), 
                            U('Home/Jipiao/insureapplynd', 'html', true), 
                            1,
                            $isover18,
                            $toubaoren);
                        $bd = $this->getbxdetail($_SESSION['insudata'][0]['prodId']);
                        if($res["responseMetaInfo"]["resultCode"] == 0 && $res1["responseMetaInfo"]["resultCode"] == 0){

                            $order = M('order');
                            $data_order = array(
                                'orderid' => $orderData['orderid'],
                                'tag' => $orderData['orderid'],
                                'total_money' => $orderData['totalorderprice'],
                                'create_time' => time() ,
                                'status' => 1,
                                'uid' => $uid,
                                'total' => $orderData['totalorderprice'],
                                'ordertype' => 4
                            );
                            $data_order1 = array(
                                'orderid' => $orderData1['orderid'],
                                'tag' => $orderData1['orderid'],
                                'total_money' => $orderData1['totalorderprice'],
                                'create_time' => time() ,
                                'status' => 1,
                                'uid' => $uid,
                                'total' => $orderData1['totalorderprice'],
                                'ordertype' => 4
                            );
                            $order->add($data_order);
                            $order->add($data_order1);
                            $pay = M("pay");
                            $data_pay = array(
                                'money' => $orderData['totalorderprice'],
                                'ratio' => '0.00',
                                'total' => $orderData['totalorderprice'],
                                'out_trade_no' => $orderData['orderid'],
                                'yunfee' => '0.00',
                                'coupon' => '0.00',
                                'uid' => $uid,
                                'addressid' => '0',
                                'create_time' => NOW_TIME,
                                'type' => 2, //货到付款
                                'status' => 1
                            );
                            $data_pay1 = array(
                                'money' => $orderData1['totalorderprice'],
                                'ratio' => '0.00',
                                'total' => $orderData1['totalorderprice'],
                                'out_trade_no' => $orderData1['orderid'],
                                'yunfee' => '0.00',
                                'coupon' => '0.00',
                                'uid' => $uid,
                                'addressid' => '0',
                                'create_time' => NOW_TIME,
                                'type' => 2, //货到付款
                                'status' => 1
                            );
                            $pay->add($data_pay);
                            $pay->add($data_pay1);

                            $insureorder = array(
                                'oid' => $orderid,
                                'extenalorderid' => $res["UnderWriteResponse"]["orderId"],
                                'paystatus' => 'UNPAID',
                                'price' => I('bxfaceprice') ,
                                'status' => '1',
                                'name' => $orderData['passengername'],
                                'insureid' => I('bx') ,
                                'prodname' => $bd['prodName'],
                                'num' => 1
                            );
                            $insureorder1 = array(
                                'oid' => $orderid1,
                                'extenalorderid' => $res1["UnderWriteResponse"]["orderId"],
                                'paystatus' => 'UNPAID',
                                'price' => I('bxfaceprice') ,
                                'status' => '1',
                                'name' => $orderData1['passengername'],
                                'insureid' => I('bx') ,
                                'prodname' => $bd['prodName'],
                                'num' => 1
                            );
                            $res = M('pay')->where(array(
                                'out_trade_no' => $orderid
                            ))->save(array(
                                'money' => $orderData['totalorderprice'] + 20 * $ckcount,
                                'total' => $orderData['totalorderprice'] + 20 * $ckcount
                            ));
                            $res = M('order')->where(array(
                                'orderid' => $orderid
                            ))->save(array(
                                'total_money' => $orderData['totalorderprice']  + 20 * $ckcount,
                                'total' => $orderData['totalorderprice']  + 20 * $ckcount
                            ));
                            $res1 = M('pay')->where(array(
                                'out_trade_no' => $orderid1
                            ))->save(array(
                                'money' => $orderData1['totalorderprice'] + 20 * $ckcount,
                                'total' => $orderData1['totalorderprice'] + 20 * $ckcount
                            ));
                            $res1 = M('order')->where(array(
                                'orderid' => $orderid1
                            ))->save(array(
                                'total_money' => $orderData1['totalorderprice'] + 20 * $ckcount,
                                'total' => $orderData1['totalorderprice'] + 20 * $ckcount
                            ));
                            if ($res && $res1) {
                                M('insure_order')->add($insureorder);
                                M('insure_order')->add($insureorder1);
                            }
                        } else {
                            //如果有任何一个保险的订单失败了，都要把所有的机票和保险取消掉。
                            $data = array(
                                'orderID'=>$orderData['externalorderid'],
                                'externalOrderID'=>$orderid,
                                'operator'=>'admin',
                                'cancelPNR'=>'false'
                            );
                            $tmp = $Filght->bpCancelOrder($data);
                            $data1 = array(
                                'orderID'=>$orderData1['externalorderid'],
                                'externalOrderID'=>$orderid1,
                                'operator'=>'admin',
                                'cancelPNR'=>'false'
                            );
                            $tmp1 = $Filght->bpCancelOrder($data1);
                            $tmp2=$insu->cancelOrder($orderid,$res["UnderWriteResponse"]["orderId"],'跟随机票取消保单(系统自动)');
                            $tmp3=$insu->cancelOrder($orderid1,$res1["UnderWriteResponse"]["orderId"],'跟随机票取消保单(系统自动)');

                            M("jipiao_order")->where(array("orderid" => $orderid, "isactive" => 1))->save(
                                  array(
                                      "orderstatus" => 'CANCELED',
                                      'isactive' => 0
                                    )
                                );
                            M("jipiao_order")->where(array("orderid" => $orderid1, "isactive" => 1))->save(
                                  array(
                                      "orderstatus" => 'CANCELED',
                                      'isactive' => 0
                                    )
                                );

                            if($res['responseMetaInfo']['resultCode'] != 0){
                              $this->error('添加订单失败:'.$res['responseMetaInfo']['reason']);
                            }else{
                              $this->error('添加订单失败:'.$res1['responseMetaInfo']['reason']);
                            }
                        }
                    }
                    $this->redirect("/Wechat/Pay/index/orderid/" . $orderData['orderid'] . "/orderid1/" . $orderData1["orderid"]);
                } else {
                    //如果第一张票出票失败，第二张票出票成功
                    if (empty($res['CreateOrderCommonResponse']) && !empty($res1['CreateOrderCommonResponse'])) {
                        $orderData = array(
                            'uid' => $uid,
                            'fromcitycn' => $_SESSION['hblist'][0]['fromCityCN'],
                            'tocitycn' => $_SESSION['hblist'][0]['toCityCN'],
                            'realid' => $realid,
                            'orderid' => $orderid, //'我方订单号',
                            'orderstatus' => "FAILED", //'订单状态',
                            'totalorderprice' => ($_SESSION['hblist'][0]['flightObject'][$k]['cabinInfo'][$g]['facePrice'] + $_SESSION['hblist'][0]['flightObject'][$k]['airportFee'] + $_SESSION['hblist'][0]['flightObject'][$k]['fuelTax']) * 1.03, //'订单总价',
                            'lxr_name' => $data['lxr_name'], //'联系人姓名',
                            'lxr_phone' => $data['lxr_phone'], //'联系人手机号',
                            //msubstr($ck['cjrid'],6,4,'utf-8',false).'-'.msubstr($ck['cjrid'],10,2,'utf-8',false).'-'.msubstr($ck['cjrid'],12,2,'utf-8',false)
                            'addtime' => time() , //'添加时间'
                            'flightno' => $_SESSION['hblist'][0]['flightObject'][$k]['flightNo'], //航班号
                            'cabinrankdetail' => $_SESSION['hblist'][0]['flightObject'][$k]['cabinInfo'][$g]['cabinRankDetail'], //舱位等级说明
                            'departuredate' => date('Y-m-d', strtotime($_SESSION['hblist'][0]['flightObject'][$k]['fromDate'])) , //机票日期
                            'departuretime' => date('H:i', strtotime($_SESSION['hblist'][0]['flightObject'][$k]['fromDate'])) , //出发时间
                            'arrivaldate' => date('Y-m-d', strtotime($_SESSION['hblist'][0]['flightObject'][$k]['toDate'])) , //抵达日期
                            'arrivaltime' => date('H:i', strtotime($_SESSION['hblist'][0]['flightObject'][$k]['toDate'])) , //抵达时间
                            'flightduration' => $_SESSION['hblist'][0]['flightObject'][$k]['flightDuration'], //时长
                            'fromairport' => $_SESSION['hblist'][0]['flightObject'][$k]['fromAirport'], //出发机场
                            'toairport' => $_SESSION['hblist'][0]['flightObject'][$k]['toAirport'], //目标机场
                            "fromtower" => $_SESSION['hblist'][0]['flightObject'][$k]['fromTower'],
                            "totower" => $_SESSION['hblist'][0]['flightObject'][$k]['toTower'],
                            "toubaoren" => json_encode($toubaoren),
                            "rule" => json_encode($_SESSION["fromRule"])
                        );
                        $orderData1 = array(
                            'uid' => $uid,
                            'fromcitycn' => $_SESSION['hblist'][1]['fromCityCN'],
                            'tocitycn' => $_SESSION['hblist'][1]['toCityCN'],
                            'realid' => $realid,
                            'orderid' => $orderid1, //'我方订单号',
                            'externalorderid' => $res1['CreateOrderCommonResponse']['orderId'], //'接口订单号',
                            'orderstatus' => $res1['CreateOrderCommonResponse']['orderStatus'], //'订单状态',
                            'totalorderprice' => ($_SESSION['hblist'][1]['flightObject'][$kk]['cabinInfo'][$f]['facePrice'] + $_SESSION['hblist'][1]['flightObject'][$kk]['airportFee'] + $_SESSION['hblist'][1]['flightObject'][$kk]['fuelTax']) * 1.03, //'订单总价',
                            'ordersettleprice' => $res1['CreateOrderCommonResponse']['totalOrderPrice'], //'订单应付金额',
                            'passengername' => $res1['CreateOrderCommonResponse']['orderMoneyDetail'][0]['passengerName'], //'乘客姓名',
                            'credentialcode' => $res1['CreateOrderCommonResponse']['orderMoneyDetail'][0]['credentialCode'], //'证件号码',
                            'passengertype' => $res1['CreateOrderCommonResponse']['orderMoneyDetail'][0]['passengerType'], //'乘客类型',
                            'faceprice' => $res1['CreateOrderCommonResponse']['orderMoneyDetail'][0]['facePrice'], //'票面价',
                            'settleprice' => $res1['CreateOrderCommonResponse']['orderMoneyDetail'][0]['settlePrice'], //'结算价',
                            'airportfee' => $res1['CreateOrderCommonResponse']['orderMoneyDetail'][0]['airportFee'], //'机建',
                            'fueltax' => $res1['CreateOrderCommonResponse']['orderMoneyDetail'][0]['fuelTax'], //'燃油',
                            'lxr_name' => $data['lxr_name'], //'联系人姓名',
                            'lxr_phone' => $data['lxr_phone'], //'联系人手机号',
                            //msubstr($ck['cjrid'],6,4,'utf-8',false).'-'.msubstr($ck['cjrid'],10,2,'utf-8',false).'-'.msubstr($ck['cjrid'],12,2,'utf-8',false)
                            'shengri' => msubstr($res1['CreateOrderCommonResponse']['orderMoneyDetail'][0]['credentialCode'], 6, 4, 'utf-8', false) . '-' . msubstr($res1['CreateOrderCommonResponse']['orderMoneyDetail'][0]['credentialCode'], 10, 2, 'utf-8', false) . '-' . msubstr($res1['CreateOrderCommonResponse']['orderMoneyDetail'][0]['credentialCode'], 12, 2, 'utf-8', false) , //'乘客出生日期',
                            'addtime' => time() , //'添加时间'
                            'flightno' => $_SESSION['hblist'][1]['flightObject'][$kk]['flightNo'], //航班号
                            'cabinrankdetail' => $_SESSION['hblist'][1]['flightObject'][$kk]['cabinInfo'][$f]['cabinRankDetail'], //舱位等级说明
                            'departuredate' => date('Y-m-d', strtotime($_SESSION['hblist'][1]['flightObject'][$kk]['fromDate'])) , //机票日期
                            'departuretime' => date('H:i', strtotime($_SESSION['hblist'][1]['flightObject'][$kk]['fromDate'])) , //出发时间
                            'arrivaldate' => date('Y-m-d', strtotime($_SESSION['hblist'][1]['flightObject'][$kk]['toDate'])) , //抵达日期
                            'arrivaltime' => date('H:i', strtotime($_SESSION['hblist'][1]['flightObject'][$kk]['toDate'])) , //抵达时间
                            'flightduration' => $_SESSION['hblist'][1]['flightObject'][$kk]['flightDuration'], //时长
                            'fromairport' => $_SESSION['hblist'][1]['flightObject'][$kk]['fromAirport'], //出发机场
                            'toairport' => $_SESSION['hblist'][1]['flightObject'][$kk]['toAirport'], //目标机场
                            "fromtower" => $_SESSION['hblist'][1]['flightObject'][$kk]['fromTower'],
                            "totower" => $_SESSION['hblist'][1]['flightObject'][$kk]['toTower'],
                            'adupnr' => $res['CreateOrderCommonResponse']['orderMoneyDetail'][0]['pnrText'],
                            "toubaoren" => json_encode($toubaoren),
                            "rule" => json_encode($_SESSION["toRule"])
                        );
                        
                        //直接取消返程订单  ，不保存数据库中
                        $data = array(
                          'orderID'=>$orderData1['externalorderid'],
                          'externalOrderID'=>$orderid1,
                          'operator'=>'admin',
                          'cancelPNR'=>'false'
                        );
                        $tmp = $Filght->bpCancelOrder($data);
                      
                        $this->error('添加订单失败:'.$res['responseMetaInfo']['reason']);
                    }
                    //如果第一张票出票成功，第二张票出票失败
                    else if (!empty($res['CreateOrderCommonResponse']) && empty($res1['CreateOrderCommonResponse'])) {
                        $orderData = array(
                            'uid' => $uid,
                            'fromcitycn' => $_SESSION['hblist'][0]['fromCityCN'],
                            'tocitycn' => $_SESSION['hblist'][0]['toCityCN'],
                            'realid' => $realid,
                            'orderid' => $orderid, //'我方订单号',
                            'externalorderid' => $res['CreateOrderCommonResponse']['orderId'], //'接口订单号',
                            'orderstatus' => $res['CreateOrderCommonResponse']['orderStatus'], //'订单状态',
                            'totalorderprice' => ($_SESSION['hblist'][0]['flightObject'][$k]['cabinInfo'][$g]['facePrice'] + $_SESSION['hblist'][0]['flightObject'][$k]['airportFee'] + $_SESSION['hblist'][0]['flightObject'][$k]['fuelTax']) * 1.03, //'订单总价',
                            'ordersettleprice' => $res['CreateOrderCommonResponse']['totalOrderPrice'], //'订单应付金额',
                            'passengername' => $res['CreateOrderCommonResponse']['orderMoneyDetail'][0]['passengerName'], //'乘客姓名',
                            'credentialcode' => $res['CreateOrderCommonResponse']['orderMoneyDetail'][0]['credentialCode'], //'证件号码',
                            'passengertype' => $res['CreateOrderCommonResponse']['orderMoneyDetail'][0]['passengerType'], //'乘客类型',
                            'faceprice' => $res['CreateOrderCommonResponse']['orderMoneyDetail'][0]['facePrice'], //'票面价',
                            'settleprice' => $res['CreateOrderCommonResponse']['orderMoneyDetail'][0]['settlePrice'], //'结算价',
                            'airportfee' => $res['CreateOrderCommonResponse']['orderMoneyDetail'][0]['airportFee'], //'机建',
                            'fueltax' => $res['CreateOrderCommonResponse']['orderMoneyDetail'][0]['fuelTax'], //'燃油',
                            'lxr_name' => $data['lxr_name'], //'联系人姓名',
                            'lxr_phone' => $data['lxr_phone'], //'联系人手机号',
                            //msubstr($ck['cjrid'],6,4,'utf-8',false).'-'.msubstr($ck['cjrid'],10,2,'utf-8',false).'-'.msubstr($ck['cjrid'],12,2,'utf-8',false)
                            'shengri' => msubstr($res['CreateOrderCommonResponse']['orderMoneyDetail'][0]['credentialCode'], 6, 4, 'utf-8', false) . '-' . msubstr($res['CreateOrderCommonResponse']['orderMoneyDetail'][0]['credentialCode'], 10, 2, 'utf-8', false) . '-' . msubstr($res['CreateOrderCommonResponse']['orderMoneyDetail'][0]['credentialCode'], 12, 2, 'utf-8', false) , //'乘客出生日期',
                            'addtime' => time() , //'添加时间'
                            'flightno' => $_SESSION['hblist'][0]['flightObject'][$k]['flightNo'], //航班号
                            'cabinrankdetail' => $_SESSION['hblist'][0]['flightObject'][$k]['cabinInfo'][$g]['cabinRankDetail'], //舱位等级说明
                            'departuredate' => date('Y-m-d', strtotime($_SESSION['hblist'][0]['flightObject'][$k]['fromDate'])) , //机票日期
                            'departuretime' => date('H:i', strtotime($_SESSION['hblist'][0]['flightObject'][$k]['fromDate'])) , //出发时间
                            'arrivaldate' => date('Y-m-d', strtotime($_SESSION['hblist'][0]['flightObject'][$k]['toDate'])) , //抵达日期
                            'arrivaltime' => date('H:i', strtotime($_SESSION['hblist'][0]['flightObject'][$k]['toDate'])) , //抵达时间
                            'flightduration' => $_SESSION['hblist'][0]['flightObject'][$k]['flightDuration'], //时长
                            'fromairport' => $_SESSION['hblist'][0]['flightObject'][$k]['fromAirport'], //出发机场
                            'toairport' => $_SESSION['hblist'][0]['flightObject'][$k]['toAirport'], //目标机场
                            "fromtower" => $_SESSION['hblist'][0]['flightObject'][$k]['fromTower'],
                            "totower" => $_SESSION['hblist'][0]['flightObject'][$k]['toTower'],
                            'adupnr' => $res['CreateOrderCommonResponse']['orderMoneyDetail'][0]['pnrText'],
                            "toubaoren" => json_encode($toubaoren),
                            "rule" => json_encode($_SESSION["fromRule"])
                        );
                        $orderData1 = array(
                            'uid' => $uid,
                            'fromcitycn' => $_SESSION['hblist'][1]['fromCityCN'],
                            'tocitycn' => $_SESSION['hblist'][1]['toCityCN'],
                            'realid' => $realid,
                            'orderid' => $orderid1, //'我方订单号',
                            'orderstatus' => "FAILED", //'订单状态',
                            'totalorderprice' => ($_SESSION['hblist'][1]['flightObject'][$kk]['cabinInfo'][$f]['facePrice'] + $_SESSION['hblist'][1]['flightObject'][$kk]['airportFee'] + $_SESSION['hblist'][1]['flightObject'][$kk]['fuelTax']) * 1.03, //'订单总价',
                            'lxr_name' => $data['lxr_name'], //'联系人姓名',
                            'lxr_phone' => $data['lxr_phone'], //'联系人手机号',
                            //msubstr($ck['cjrid'],6,4,'utf-8',false).'-'.msubstr($ck['cjrid'],10,2,'utf-8',false).'-'.msubstr($ck['cjrid'],12,2,'utf-8',false)
                            'addtime' => time() , //'添加时间'
                            'flightno' => $_SESSION['hblist'][1]['flightObject'][$kk]['flightNo'], //航班号
                            'cabinrankdetail' => $_SESSION['hblist'][1]['flightObject'][$kk]['cabinInfo'][$f]['cabinRankDetail'], //舱位等级说明
                            'departuredate' => date('Y-m-d', strtotime($_SESSION['hblist'][1]['flightObject'][$kk]['fromDate'])) , //机票日期
                            'departuretime' => date('H:i', strtotime($_SESSION['hblist'][1]['flightObject'][$kk]['fromDate'])) , //出发时间
                            'arrivaldate' => date('Y-m-d', strtotime($_SESSION['hblist'][1]['flightObject'][$kk]['toDate'])) , //抵达日期
                            'arrivaltime' => date('H:i', strtotime($_SESSION['hblist'][1]['flightObject'][$kk]['toDate'])) , //抵达时间
                            'flightduration' => $_SESSION['hblist'][1]['flightObject'][$kk]['flightDuration'], //时长
                            'fromairport' => $_SESSION['hblist'][1]['flightObject'][$kk]['fromAirport'], //出发机场
                            'toairport' => $_SESSION['hblist'][1]['flightObject'][$kk]['toAirport'], //目标机场
                            "fromtower" => $_SESSION['hblist'][1]['flightObject'][$kk]['fromTower'],
                            "totower" => $_SESSION['hblist'][1]['flightObject'][$kk]['toTower'],
                            "toubaoren" => json_encode($toubaoren),
                            "rule" => json_encode($_SESSION["toRule"])
                        );

                        $data = array(
                          'orderID'=>$orderData['externalorderid'],
                          'externalOrderID'=>$orderid,
                          'operator'=>'admin',
                          'cancelPNR'=>'false'
                        );
                        $tmp = $Filght->bpCancelOrder($data);

                        //其中有一张票订票失败，不买保险直接购票失败，不保存数据库中
                        $this->error('添加订单失败:'.$res1['responseMetaInfo']['reason']);
                    }
                    //如果两张票都出票失败
                    else {
                        $this->error('添加订单失败:' . $res['responseMetaInfo']['reason']);
                    }
                }
            } else {
                if (!empty($res['CreateOrderCommonResponse'])) {

                    for ($i = 0; $i < count($res['CreateOrderCommonResponse']['orderMoneyDetail']); $i++) { 
                        if($i != 0){
                          $passengername .= "|";
                          $credentialcode .= "|";
                          $passengertype .= "|";
                          $faceprice .= "|";
                          $settleprice .= "|";
                          $airportfee .= "|";
                          $fueltax .= "|";
                          $shengri .= "|";
                        }
                        $passengername .= $res['CreateOrderCommonResponse']['orderMoneyDetail'][$i]["passengerName"];
                        $credentialcode .= $res['CreateOrderCommonResponse']['orderMoneyDetail'][$i]["credentialCode"];
                        $passengertype .= $res['CreateOrderCommonResponse']['orderMoneyDetail'][$i]["passengerType"];
                        $faceprice .= $res['CreateOrderCommonResponse']['orderMoneyDetail'][$i]["facePrice"];
                        $settleprice .= $res['CreateOrderCommonResponse']['orderMoneyDetail'][$i]["settlePrice"];
                        $airportfee .= $res['CreateOrderCommonResponse']['orderMoneyDetail'][$i]['airportFee'];
                        $fueltax .= $res['CreateOrderCommonResponse']['orderMoneyDetail'][$i]['fuelTax'];
                        $shengri .= msubstr($res['CreateOrderCommonResponse']['orderMoneyDetail'][$i]['credentialCode'],6,4,'utf-8',false).'-'.msubstr($res['CreateOrderCommonResponse']['orderMoneyDetail'][$i]['credentialCode'],10,2,'utf-8',false).'-'.msubstr($res['CreateOrderCommonResponse']['orderMoneyDetail'][$i]['credentialCode'],12,2,'utf-8',false);
                        $adupnr = $res['CreateOrderCommonResponse']['orderMoneyDetail'][0]['pnrText'];
                      }

                    $orderData = array(
                        'uid' => $uid,
                        'fromcitycn' => $_SESSION['hblist'][0]['fromCityCN'],
                        'tocitycn' => $_SESSION['hblist'][0]['toCityCN'],
                        'realid' => $realid,
                        'orderid' => $orderid, //'我方订单号',
                        'externalorderid' => $res['CreateOrderCommonResponse']['orderId'], //'接口订单号',
                        'orderstatus' => $res['CreateOrderCommonResponse']['orderStatus'], //'订单状态',
                        'totalorderprice'=>$res['CreateOrderCommonResponse']['totalOrderPrice']*1.03, //'订单总价',
                        'ordersettleprice'=>$res['CreateOrderCommonResponse']['totalOrderPrice'], //'订单应付金额',
                        'passengername'=>$passengername, //'乘客姓名',
                        'credentialcode'=>$credentialcode, //'证件号码',
                        'passengertype'=>$passengertype, //'乘客类型',
                        'faceprice'=>$faceprice, //'票面价',
                        'settleprice'=>$settleprice, //'结算价',
                        'airportfee'=>$airportfee, //'机建',
                        'fueltax'=>$fueltax, //'燃油',
                        'lxr_name' => $data['lxr_name'], //'联系人姓名',
                        'lxr_phone' => $data['lxr_phone'], //'联系人手机号',
                        //msubstr($ck['cjrid'],6,4,'utf-8',false).'-'.msubstr($ck['cjrid'],10,2,'utf-8',false).'-'.msubstr($ck['cjrid'],12,2,'utf-8',false)
                        'shengri'=>$shengri, //'乘客出生日期',
                        'addtime' => time() , //'添加时间'
                        'flightno' => $_SESSION['hblist'][0]['flightObject'][$k]['flightNo'], //航班号
                        'flightno1' => $_SESSION['hblist'][1]['flightObject'][$kk]['flightNo'], //航班号
                        'cabinrankdetail' => $_SESSION['hblist'][0]['flightObject'][$k]['cabinInfo'][$g]['cabinRankDetail'], //舱位等级说明
                        'departuredate' => date('Y-m-d', strtotime($_SESSION['hblist'][0]['flightObject'][$k]['fromDate'])) , //机票日期
                        'departuretime' => date('H:i', strtotime($_SESSION['hblist'][0]['flightObject'][$k]['fromDate'])) , //出发时间
                        'arrivaldate' => date('Y-m-d', strtotime($_SESSION['hblist'][0]['flightObject'][$k]['toDate'])) , //抵达日期
                        'arrivaltime' => date('H:i', strtotime($_SESSION['hblist'][0]['flightObject'][$k]['toDate'])) , //抵达时间
                        'flightduration' => $_SESSION['hblist'][0]['flightObject'][$k]['flightDuration'], //时长
                        'fromairport' => $_SESSION['hblist'][0]['flightObject'][$k]['fromAirport'], //出发机场
                        'toairport' => $_SESSION['hblist'][0]['flightObject'][$k]['toAirport'], //目标机场
                        "fromtower" => $_SESSION['hblist'][0]['flightObject'][$k]['fromTower'],
                        "totower" => $_SESSION['hblist'][0]['flightObject'][$k]['toTower'],
                        'adupnr'=>$adupnr,
                        "toubaoren" => json_encode($toubaoren),
                        "rule" => json_encode($_SESSION["fromRule"])
                    );
                    $model = M('jipiao_order');
                    $model->add($orderData);

                    //添加保险
                    $insu = new \Api\Controller\InsuController();
                    if (!empty($_SESSION['insudata'])) {
                        $res = $insu->createOrderType2(
                            $_SESSION['insudata'][0]['prodId'], 
                            $orderid, 
                            $_SESSION['insudata'][0]['price'], 
                            '/Home/Jipiao/insurepaynd.html', 
                            '/Home/Jipiao/insureapplynd.html', 
                            1,
                            $isover18,
                            $toubaoren);
                        $bd = $this->getbxdetail($_SESSION['insudata'][0]['prodId']);
                        if ($res["responseMetaInfo"]["resultCode"] == 0) {
                            $order = M('order');
                            $data_order = array(
                                'orderid' => $orderData['orderid'],
                                'tag' => $orderData['orderid'],
                                'total_money' => $orderData['totalorderprice'],
                                'create_time' => time() ,
                                'status' => 1,
                                'uid' => $uid,
                                'total' => $orderData['totalorderprice'],
                                'ordertype' => 4
                            );
                            $order->add($data_order);
                            $pay = M("pay");
                            $data_pay = array(
                                'money' => $orderData['totalorderprice'],
                                'ratio' => '0.00',
                                'total' => $orderData['totalorderprice'],
                                'out_trade_no' => $orderData['orderid'],
                                'yunfee' => '0.00',
                                'coupon' => '0.00',
                                'uid' => $uid,
                                'addressid' => '0',
                                'create_time' => NOW_TIME,
                                'type' => 2, //货到付款
                                'status' => 1
                            );
                            $pay->add($data_pay);

                            $insureorder = array(
                                'oid' => $orderid,
                                'extenalorderid' => $res["UnderWriteResponse"]["orderId"],
                                'paystatus' => 'UNPAID',
                                'price' => $_SESSION['insudata'][0]['price'],
                                'status' => '1',
                                'name' => $orderData['passengername'],
                                'insureid' => $_SESSION['insudata'][0]['prodId'],
                                'prodname' => $bd['prodName'],
                                'num' => 1
                            );
                            $res = M('pay')->where(array(
                                'out_trade_no' => $orderid
                            ))->save(array(
                                'money' => $orderData['totalorderprice'] + 20 * $ckcount,
                                'total' => $orderData['totalorderprice'] + 20 * $ckcount
                            ));
                            $res = M('order')->where(array(
                                'orderid' => $orderid
                            ))->save(array(
                                'total_money' => $orderData['totalorderprice'] + 20 * $ckcount,
                                'total' => $orderData['totalorderprice'] + 20 * $ckcount
                            ));
                            if ($res) {
                                M('insure_order')->add($insureorder);
                            }
                        } else {
                            //如果购买保险失败，则要取消机票，并告知原因
                            $data = array(
                              'orderID'=>$orderData['externalorderid'],
                              'externalOrderID'=>$orderid,
                              'operator'=>'admin',
                              'cancelPNR'=>'false'
                            );
                            $tmp = $Filght->bpCancelOrder($data);
                            M("jipiao_order")->where(array("orderid" => $orderid, "isactive" => 1))->save(
                              array(
                                  "orderstatus" => 'CANCELED',
                                  'isactive' => 0
                                )
                            );
                            $this->error('添加订单失败:'.$res['responseMetaInfo']['reason']);
                        }
                        $this->redirect("/Wechat/Pay/index/orderid/" . $orderData['orderid']);
                    }
                }else{
                    $this->error('添加订单失败:' . $res['responseMetaInfo']['reason']);
                }
            }
        } else {

            $addreses = M("address")->where("uid=%d and status>=0", $uid)->select();
            $this->assign("default_id", M("customer")->where("id=%d", $uid)->getField("address_id"));
            $this->assign('address', $addreses);
            $this->assign('data', $data);
            $cjr = M('cjr')->where("uid=%d", $uid)->select();
            $this->assign('cjr', $cjr);
            //添加保险
            $insu = new \Api\Controller\InsuController();
            $insudata = $insu->queryProd();
            $_SESSION['insudata'] = $insudata;
            $this->assign('bxdata', $insudata);
            $this->display();
        }
    }

    public function getHejiaInfo(){
        $direct = $_GET["direct"];//方向 0 去程 1 返程
        $k = $_GET["k"];//航班
        $g = $_GET["g"] - 1;//座次
        $Filght = new \Api\Controller\FlightController();
        if($direct == 0){
            unset($_SESSION["fromRule"]);
            $ddate = explode("T", $_SESSION['hblist'][0]['flightObject'][$k]['fromDate']);
            $dtime = explode(":00.000+08:00", $ddate[1]);
            $adate = explode("T", $_SESSION['hblist'][0]['flightObject'][$k]['toDate']);
            $atime = explode(":00.000+08:00", $adate[1]);
            $fromRule = $Filght->bpHejia(array(
            "flightNo" => $_SESSION['hblist'][0]['flightObject'][$k]["flightNo"],
            "shareFlightNo" => $_SESSION['hblist'][0]['flightObject'][$k]['shareFlightNo'],
            'cabinRank' => $_SESSION['hblist'][0]['flightObject'][$k]['cabinInfo'][$g]['cabinRank'],
            'cabinCode' => $_SESSION['hblist'][0]['flightObject'][$k]['cabinInfo'][$g]['cabinCode'],
            'departureDate' => $ddate[0],
            'departureTime' => $dtime[0],
            'arrivalDate' => $adate[0],
            'arrivalTime' => $atime[0],
            'fromAirport' => $_SESSION['hblist'][0]['flightObject'][$k]['fromAirport'],
            'toAirport' => $_SESSION['hblist'][0]['flightObject'][$k]['toAirport']
            ));
            $fromRule = $fromRule[0]["endorseAndRefundRule"][0];
            $_SESSION["fromRule"] = $fromRule;
            echo json_encode($fromRule);
        }

        if($direct == 1){
            unset($_SESSION["toRule"]);
            $ddate = explode("T", $_SESSION['hblist'][1]['flightObject'][$k]['fromDate']);
            $dtime = explode(":00.000+08:00", $ddate[1]);
            $adate = explode("T", $_SESSION['hblist'][1]['flightObject'][$k]['toDate']);
            $atime = explode(":00.000+08:00", $adate[1]);
            $toRule = $Filght->bpHejia(array(
            "flightNo" => $_SESSION['hblist'][1]['flightObject'][$k]["flightNo"],
            "shareFlightNo" => $_SESSION['hblist'][1]['flightObject'][$k]['shareFlightNo'],
            'cabinRank' => $_SESSION['hblist'][1]['flightObject'][$k]['cabinInfo'][$g]['cabinRank'],
            'cabinCode' => $_SESSION['hblist'][1]['flightObject'][$k]['cabinInfo'][$g]['cabinCode'],
            'departureDate' => $ddate[0],
            'departureTime' => $dtime[0],
            'arrivalDate' => $adate[0],
            'arrivalTime' => $atime[0],
            'fromAirport' => $_SESSION['hblist'][1]['flightObject'][$k]['fromAirport'],
            'toAirport' => $_SESSION['hblist'][1]['flightObject'][$k]['toAirport']
            ));
            $toRule = $toRule[0]["endorseAndRefundRule"][0];
            $_SESSION["toRule"] = $toRule;
            echo json_encode($toRule);
        }
    }



    
//修改地址
  public function addset(){
    if(IS_POST){
      $data=I('post.');
//      dump($data);  
      $add=M('address')->where(array('id'=>$data['addid']))->find();
      if(!$add){
        $info['status']=0;
        $info['msg']='未找到地址!';
        $this->ajaxReturn($info);
      }else{
        $info['status']=1;
        $info['msg']='找到了！';
        $info['add']=$add;
        $this->ajaxReturn($info);
      }     
    }else{
      die('no access!');
    }
  }
  
  //保存地址
  public function addsave(){
    if(IS_POST){
      $data=I('post.');
//      dump($data);  
      $add=M('address')->where(array('id'=>$data['id']))->find();
      if(!$add){
        $info['status']=0;
        $info['msg']='未找到地址!';
        $this->ajaxReturn($info);
      }else{
        $re=M('address')->save($data);
        if($re!==FALSE){
          $info['status']=1;
          $info['msg']='修改成功！';
          
        }else{
          $info['status']=0;
          $info['msg']='修改失败，请重试！';
        }       
        $this->ajaxReturn($info);
      }     
    }else{
      die('no access!');
    }
  }
  /**
   * 退票
   */
  public function refund(){
    $oid=I('oid');
    $applytype=I('applytype');
    $Filght =  new \Api\Controller\FlightController();
    $order=M('jipiao_order')->where(array('orderid'=>$oid, "isactive"=>1))->find();
    if(!$order){
      $this->error("订单号不正确!");
    }
    //
    $data['orderid']=$order['externalorderid']; //内部订单号
    $data['externalorderid']=$order['orderid']; //外部订单号
    $data['applytype']='TP';//退票
    $data['refundreason']=$applytype;//退票类型
    // $data['name']=$order['passengername'];//姓名
    // $data['credentialcode']=$order['credentialcode'];//身份证
    // $tkno=explode('|',$order['ticketno']);
    // $data['ticketno']=$tkno[1];//票号
    $data["order"] = $order;
    $data['afterrefundurl']='/Home/Jipiao/refundnd/orderid/'.$oid.".html";//回调地址
    if(!$order['iswf']){
      $data['flightrange']=$order['fromairport'].'-'.$order['toairport'];
    }else{
      $data['flightrange']=$order['fromairport'].'-'.$order['toairport'].'|'.$order['fromairport1'].'-'.$order['toairport1'];//身份证
    }
    $result=$Filght->refund($data);
    if($result['RefundTicketResponse']['applyStatus'] == 'SUCCESS'){
      //申请成功
      $jipiao_order = M('jipiao_order')->where(array('orderid'=>$oid, "isactive"=>1))->find();
      $jipiao_order['origorderId'] = $res['RefundTicketResponse']['originalOrderID'];
      $jipiao_order['externalorderid'] = $res['RefundTicketResponse']['refundOrderID'];
      $jipiao_order['orderstatus'] = 'WAIT_REFUND';
      M('jipiao_order')->save($jipiao_order);
      if($jipiao_order["departuredate"] != date('Y-m-d', time())){
        //保险退款
        $insu =  new \Api\Controller\InsuController();
        $insureorder=M('insure_order')->where(array('oid'=>$oid))->find();

        $insureorderNameArray = explode("|", $insureorder["name"]);
        $insureorderInsureidArray = explode("|", $insureorder["insureid"]);

        $insu->closeOrder($oid,$insureorder['extenalorderid'],'机票退款跟随保险退款(系统自动)',$insureorderNameArray,$insureorderInsureidArray,U('home/jipiao/insureclosednd'));
        M('insure_order')->where(array('oid'=>$oid))->save(array('status'=>5, "paystatus"=>'PROCESSING'));
      }

      $this->success("申请成功,退票成功后退还金额会存入您的余额中!");
    }else{
      //申请失败
      $this->error("申请失败".$result['responseMetaInfo']['reason']);
      Log::write("退票错误,结果:".$result['responseMetaInfo']['reason']);
    }
  }

  /*
     * 改签
     */
    public function endorse(){
        header("Content-type: text/html; charset=utf-8");
        $Filght =  new \Api\Controller\FlightController();
        $order=M('jipiao_order')->where(array('orderid'=>I('get.oid'), "isactive"=>1))->find();
        $data = array(
                'fromCity'=>$order['fromcitycn'],
                'toCity'=>$order['tocitycn'],
                'fromDate'=>I('get.startdate'),
                'backDate'=>I('get.backdate')
            );
        if(!$order){
          $this->error('订单异常!');
        }
        if($order['iswf']=='1'){
          $cache = $Filght->bpwfselect($data);
          //根据航空公司对航班进行航班配对
          $sarr=$cache[0];
          $earr=$cache[1];
          $list=$cache;
          // unset($list[1]['flightObject']);
          // foreach ($sarr['flightObject'] as $k => $v) {
          //   $scom=substr($v['flightNo'], 0,2);
          //   foreach ($earr['flightObject'] as $kk => $vv) {
          //     $ecom=substr($vv['flightNo'], 0,2);
          //     if($scom==$ecom){
          //       $list[1]['flightObject'][$k]=$vv;
          //     }
          //   }
          // }
        }else{
            $list = $Filght->bpSelect($data);
        }
        $hbtype = $order['iswf']==1?'wf':'dc';

        if($order['iswf'] != 1){
              $companyto = substr($order["flightno"], 0, 2);
              $data = array(
                'fromCity'=>$order['fromcitycn'],
                'toCity'=>$order['tocitycn'],
                'fromDate'=>I('get.startdate'),
                'backDate'=>I('get.backdate'),
                'hbtype'=>$hbtype,
                "airCompany" => $companyto
              );
              $list = $Filght->bpSelect($data);
              $cache = $list;
        }else{
           $companyto = substr($order["flightno"], 0, 2);
          $companyback = substr($order["flightno1"], 0, 2);
          $data = array(
                'fromCity'=>$order['fromcitycn'],
                'toCity'=>$order['tocitycn'],
                'fromDate'=>I('get.startdate'),
                'backDate'=>I('get.backdate'),
                'hbtype'=>"wf",
                "airCompany" => $companyto,
                "airCompany1" => $companyback
              );

          $list = $Filght->bpwfselect($data);
          //根据航空公司对航班进行航班配对
          $sarr=$list[0];
          $earr=$list[1];
          $cache=$list;
          // unset($cache[1]['flightObject']);
          // foreach ($sarr['flightObject'] as $k => $v) {
          //   $scom=substr($v['flightNo'], 0,2);
          //   foreach ($earr['flightObject'] as $kk => $vv) {
          //     $ecom=substr($vv['flightNo'], 0,2);
          //     if($scom==$ecom){
          //       $cache[1]['flightObject'][$k]=$vv;
          //     }
          //   }
          // }
        }

        if($hbtype =='dc'){
          $this->assign("toPrice", $order["faceprice"]);
        }else{
          $this->assign("backPrice", $order["faceprice"]/2);
          $this->assign("toPrice", $order["faceprice"]/2);
        }
        unset($_SESSION['hblist']);
        $_SESSION['hblist']=$list;
        $this->assign('list', $list);   
        $this->assign('data',$data);  
        $this->assign("iswf", $order['iswf']);
        $this->assign("order", $order);
        $this->display();
    }
  /**
   * 改签票务信息
   */
  public function endorseshow(){
     header("Content-type: text/html; charset=utf-8");
        if (!is_login()) {
            $this->redirect("User/login");
            exit;
        }
    $uid = is_login();

    $order = M("jipiao_order")->where(array("orderid" => I("oid"), "isactive" => 1)) -> find();

      $addreses = M("address")->where("uid=%d and status>=0", $uid)->select();
    $this->assign("default_id", M("customer")->where("id=%d", $uid)->getField("address_id"));
    $this->assign('address', $addreses);
        $this->assign('data', $data);
    $cjr=M('cjr')->where("uid=%d", $uid)->select();
    $this->assign('cjr',$cjr);

    if($order["iswf"] != 1){
      $this->assign("toPrice", $order["faceprice"]);
    }else{
      $this->assign("backPrice", $order["faceprice"]/2);
      $this->assign("toPrice", $order["faceprice"]/2);
    }

        $this->display();
  }
  /**
   * 调用改签接口操作
   */
  public function endorseopt(){
    header("Content-type: text/html; charset=utf-8");
    $orderid=$_GET['orderid'];
    $k=$_GET['o'];
    $kk=$_GET['o1'];
    $g=I('go')-1;
    $f=I('back')-1;
    $order=M('jipiao_order')->where(array('orderid'=>$orderid, 'isactive'=>1))->find();
    $data['orderid']=$order['orderid'];//航天平台订单号
    $data['externalorderid']=$order['externalorderid'];//机票平台订单号
    
    $data['pnr']=$order['adupnr']?$order['adupnr']:$order['chdpnr'];//pnr
    
    // $data['passengername']=$order['passengername'];//   乘客姓名
    // $data['documentno']=$order['credentialcode'];//   证件号码
    // $tkno=explode('|',$order['ticketno']);
    // $data['ticketno']=$tkno[1];//票号
    // $data['passengertype']=$order['passengertype'];//   票序号

    $data["order"] = $order;

    $passengernameArray = explode("|", $order["passengername"]);
    $ckcount = count($passengernameArray);

    $data['flightno']=$_SESSION['hblist'][0]['flightObject'][$k]['flightNo'];//航班号
    $data['origflightno']=$order['flightno'];     //原航班号
    $data['departmentdate']=date('Y-m-d H:i', strtotime($_SESSION['hblist'][0]['flightObject'][$k]['toDate']));     //出发时间
    $data['arrivaldate']=date('Y-m-d H:i', strtotime($_SESSION['hblist'][0]['flightObject'][$k]['toDate']));      //到达时间
    $data['classcode']=$_SESSION['hblist'][0]['flightObject'][$k]['cabinInfo'][$g]['cabinCode'];      //仓位代码
    
    $Filght =  new \Api\Controller\FlightController();
    $result=$Filght->endorse($data);
    //die();
        // Log::write("改签数据:".json_encode($data));
        // Log::write("改签结果内容:".json_encode($result));
    
    if($result['EndorseFlightOrderResponse']['endorseStatus'] == 'SUCCESS'){
            $oldjipiao = M("jipiao_order")->where(array("orderid" => $orderid, "isactive"=>1))->find();
            $oldjipiao["isactive"] = 0;
            M("jipiao_order")->save($oldjipiao);
            $newjipiao = array();
            $newjipiao["uid"] = $oldjipiao["uid"];
            $newjipiao["realid"] = $oldjipiao["realid"];
            $newjipiao["orderid"] = $oldjipiao["orderid"];
            $newjipiao["externalorderid"] = $result['EndorseFlightOrderResponse']["endorseOrderId"];
            $newjipiao["orderstatus"] = "ENDORSE_WAIT_AUDIT";
            $newjipiao["totalorderprice"] = $oldjipiao["totalorderprice"];
            $newjipiao["ordersettleprice"] = $oldjipiao["ordersettleprice"];
            $newjipiao["passengername"] = $oldjipiao["passengername"];
            $newjipiao["credentialcode"] = $oldjipiao["credentialcode"];
            $newjipiao["passengertype"] = $oldjipiao["passengertype"];
            $newjipiao["faceprice"] = $oldjipiao['faceprice'];
            $newjipiao["extrafee"] =$oldjipiao['extrafee'];
            $newjipiao["settleprice"] = $oldjipiao['settleprice'];
            $newjipiao["airportfee"] = $oldjipiao["airportfee"];
            $newjipiao["fueltax"] = $oldjipiao["fueltax"];
            $newjipiao["lxr_name"] = $oldjipiao["lxr_name"];
            $newjipiao["lxr_phone"] = $oldjipiao["lxr_phone"];
            $newjipiao["shengri"] = $oldjipiao["shengri"];
            $newjipiao["addtime"] = time();
            $newjipiao["fromcitycn"] = $oldjipiao["fromcitycn"];
            $newjipiao["tocitycn"] = $oldjipiao["tocitycn"];
            $newjipiao["flightno"] =$_SESSION['hblist'][0]['flightObject'][$k]['flightNo'];
            $newjipiao["cabinrankdetail"] = $_SESSION['hblist'][0]['flightObject'][$k]['cabinInfo'][$g]['cabinRankDetail'];
            $newjipiao["departuredate"] = date('Y-m-d', strtotime($_SESSION['hblist'][0]['flightObject'][$k]['fromDate']));
            $newjipiao["departuretime"] = date('H:i', strtotime($_SESSION['hblist'][0]['flightObject'][$k]['fromDate']));
            $newjipiao["arrivaldate"] = date('Y-m-d', strtotime($_SESSION['hblist'][0]['flightObject'][$k]['toDate']));
            $newjipiao["arrivaltime"] = date('H:i', strtotime($_SESSION['hblist'][0]['flightObject'][$k]['toDate']));
            $newjipiao["flightduration"] = $_SESSION['hblist'][0]['flightObject'][$k]['flightDuration'];
            $newjipiao["fromairport"] = $_SESSION['hblist'][0]['flightObject'][$k]['fromAirport'];
            $newjipiao["toairport"] = $_SESSION['hblist'][0]['flightObject'][$k]['toAirport'];
            $newjipiao["fromtower"] = $_SESSION['hblist'][0]['flightObject'][$k]['fromTower'];
            $newjipiao["totower"] = $_SESSION['hblist'][0]['flightObject'][$k]['toTower'];
            $newjipiao["adupnr"] = $oldjipiao["adupnr"];
            $newjipiao["chdPnr"] = $oldjipiao["chdPnr"];
            $newjipiao["ticketno"] = $oldjipiao["ticketno"];
            $newjipiao["origorderId"] = $oldjipiao["externalorderid"];
            $newjipiao["toubaoren"] = $oldjipiao["toubaoren"];
            $newjipiao["payamount"] = 0.00;
            $newjipiao["rule"] = $oldjipiao["rule"];
      
            M("jipiao_order")->add($newjipiao);

            $insu =  new \Api\Controller\InsuController();
            $insureorder=M('insure_order')->where(array('oid'=>$orderid))->find();
            //如果机票是在当天改签的，则无法退掉原来的保险。
            if($oldjipiao["departuredate"] != date('Y-m-d', time()))
            {
                $insureorderNameArray = explode("|", $insureorder["name"]);
                $insureorderInsureidArray = explode("|", $insureorder["insureid"]);
                
                $insu->closeOrder($orderid,$insureorder['extenalorderid'],'机票退款跟随保险退款(系统自动)',$insureorderNameArray, $insureorderInsureidArray,U('home/jipiao/insureclosednd'));
            }

            $toubaoren = json_decode($oldjipiao["toubaoren"], true);
            if(!empty($toubaoren["name"])){
              $isover18 = 0;
            }else{
              $isover18 = 1;
            }

            //接口只会给我们返回一个保险产品，直接写死用第一个就可以
            $neworderid = date('Ym', time()) . time() . is_login();
            $insudata=$insu->queryProd();
            $bxorderid=$insu->createOrder(
              $insudata[0]["prodId"],
              $neworderid,
              $insudata[0]["price"],
              U('Home/Jipiao/insurepaynd', 'html', true),
              U('Home/Jipiao/insureapplynd', 'html', true),
              1,
              $isover18,//$isover18
              $toubaoren,//$toubaoren
              $orderid);
            if($bxorderid){
              $insureorder["extenalorderid"] = $bxorderid;
              $insureorder["paystatus"] = "UNPAID";
              $insureorder["status"] = "1";
              $insureorder["newoid"] = $neworderid;
              M("insure_order")->save($insureorder);

              //当天改签需要另外支付保险费用
              if($oldjipiao["departuredate"] == date('Y-m-d', time())){
                $newjipiao['payamount'] = (float)$insudata[0]["price"] * $ckcount + $newjipiao['payamount'];
              }

              M("jipiao_order")->where(array("orderid" => $orderid, "isactive"=>1))->setField('payamount', $newjipiao['payamount']);

            }else{
              if($oldjipiao["departuredate"] != date('Y-m-d', time())){
                $insureorder["paystatus"] = "PROCESSING";
                $insureorder["status"] = "5";
                M("insure_order")->save($insureorder);
              }
            }

            $this->success('改签申请成功!',U('UserMallOrder/jipiao'));
      
    }else{
      $this->error($result['responseMetaInfo']['reason'],U('UserMallOrder/jipiao'));
      Log::write("改签错误,结果:".json_encode($result));
    }
  }
  
  /**
   * 对象->数组
   */
  private function object_array($array){
    if(is_object($array)){
      $array = (array)$array;
    }
    if(is_array($array)){
      foreach($array as $key=>$value){
        $array[$key] = $this->object_array($value);
      }
    }
    return $array;
  }
  
    /**
     * 取消订单
     */
    public function clear(){
        header("Content-type: text/html; charset=utf-8");
        if (!is_login()) {
            $this->redirect("User/login");
            exit;
        }
        $oid = I('get.oid');
        $model = M('jipiao_order');
        $info = $model->where(array('orderid'=>$oid, "isactive"=>1))->find();

        if($info){
            $Filght =  new \Api\Controller\FlightController();
            $data = array(
                'orderID'=>$info['externalorderid'],
                'externalOrderID'=>$oid,
                'operator'=>'admin',
                'cancelPNR'=>'false'
            );
            $res = $Filght->bpCancelOrder($data);
            if($res['CancelOrderResponse']['orderCancelStatus'] == 'SUCCESS'){
                $model->where(array('orderid'=>$oid, 'uid'=>is_login()))->save(array('orderstatus'=>'CANCELED'));

                $insureorder=M('insure_order')->where(array('oid'=>$oid))->find();
                if($insureorder){
                  $insu =  new \Api\Controller\InsuController();
                  $insures=$insu->cancelOrder($oid,$insureorder['extenalorderid'],'跟随机票取消保单(系统自动)');
                  if($insures){
                    M('insure_order')->where(array('oid'=>$oid))->save(array('status'=>0));
                  }
                }

                $this->success('订单取消成功');
            }else{
                $this->error('订单取消失败:'.$res['responseMetaInfo']['reason']);
            }
        }else{
            $this->error('订单不存在！');
            exit;
        }
    }
/**
 * 保存联系人
 */
  public function savelxr(){
    $data=$_POST;
    $uid= is_login();
    $data['uid']=$uid;
    $data['cellphone']=$data['phone'];
    $data['create_time']=time();
    $result=M('address')->add($data);
    if($result){
      $info['status']=1;
      $info['msg']='保存成功!';
    }else{
      $info['status']=1;
      $info['msg']='保存失败,请重试!';
    }
    $this->ajaxReturn($info);
  }
  /**
   * 保存乘机人
   */
  public function savecjr(){
    $post=$_POST;
    $uid= is_login();
    $data['uid']=$uid;
    $data['cjrname']=$_POST['chengke'];
    $data['cjrmobile']=$_POST['phone'];
    $data['cjrfm']=$_POST['fm'];
    $data['cjrid']=$_POST['shenfenzheng'];
    $result=M('cjr')->add($data);
    if($result){
      $info['status']=1;
      $info['msg']='保存成功!';
    }else{
      $info['status']=1;
      $info['msg']='保存失败,请重试!';
    }
    $this->ajaxReturn($info);
  }
  /**
   * 获取保险详细信息
   */
  public function getbxdetail($id=0){
    if($id==0){
      $pid=I('pid');
    }else{
      $pid=$id;
    }
    $data=array();
    foreach ($_SESSION['insudata'] as $k => $v) {
      if($v['prodId']==$pid){
        $data=$v;
      }
    }
    if($id==0){
      $this->ajaxReturn($data);
    }else{
      return $data;
    }
  }


  public function checkorder()
  {
    $data["orderID"] = "1608101229141461";
    $Filght =  new \Api\Controller\FlightController();
    $result=$Filght->bpState($data);

    echo 1;
  }

  public function test(){
    print_r(I("post."));
  }
}