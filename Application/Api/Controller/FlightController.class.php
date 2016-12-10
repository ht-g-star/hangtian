<?php
namespace Api\Controller;

use Think\Controller;
use Think\Log;
/**
 * 航班接口
 * @authors Your Name (you@example.org)
 * @date    2016-06-06 21:37:37
 * @version $Id$
 */

class FlightController extends Controller {

    private $userCon;
    private $apiurl;
    private $callbackHost;

    public function _initialize() {
        parent::_initialize();
        $time = time();
        $this->userCon = array(
            'userName'=>'57847e7845cecdb70bd730c9',
            'signCode'=>md5("tzOpenapisignCode8f3a7721timeStamp".$time."userName57847e7845cecdb70bd730c9tzOpenapi"),
            'timeStamp'=>$time
        );
        $this->apiurl = 'http://api.tdxinfo.com/service/flight/domestic';
        $this->callbackHost = "http://www.wx738.com";
    }


    /**
     * 白屏查询线路接口
     */
    public function bpSelect($data){
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                    <TzRequest xmlns="http://www.travelzen.com/flight/base" xmlns:domestic="http://www.travelzen.com/flight/domestic" xmlns:insure="http://www.travelzen.com/flight/insure" xmlns:international="http://www.travelzen.com/flight/international" xmlns:basic="http://www.travelzen.com/flight/basic">
                        <requestMetaInfo>
                            <userName>'.$this->userCon['userName'].'</userName>
                            <signCode>'.$this->userCon['signCode'].'</signCode>
                            <timeStamp>'.$this->userCon['timeStamp'].'</timeStamp>
                            <responseDataType>JSON</responseDataType>
                        </requestMetaInfo>
                        <requestEntity xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="domestic:FlightSearchV2Request">
                            <flightRange>
                                <fromCity>'.$data['fromCity'].'</fromCity>
                                <toCity>'.$data['toCity'].'</toCity>
                                <fromDate>'.$data['fromDate'].'</fromDate>
                                <airCompany>'.$data['airCompany'].'</airCompany>
                                <cabinRank>'.$data['cabinRank'].'</cabinRank>
                            </flightRange>
                            <lowerstPrice>'.$data['lowerstPrice'].'</lowerstPrice>
                            <openingTime>'.$data['openingTime'].'</openingTime>
                            <changePNR>'.$data['changePNR'].'</changePNR>
                            <cabinRank>'.$data['cabinRank'].'</cabinRank>
                        </requestEntity>
                    </TzRequest>';
        $res = $this->postXmlCurl($xml, $this->apiurl);
        Log::write("白屏查询线路接口数据提交:".$xml);
        Log::write("白屏查询线路接口:".$res);
        $res = json_decode($res, true);
        if($res['FlightSearchV2Response']['flightSegmentResult']){
            return $res['FlightSearchV2Response']['flightSegmentResult'];
        }else{
            return false;
        }
    }
/**
 * 往返查询
 */
  public function bpwfselect($data){
    $xml='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
        <TzRequest xmlns="http://www.travelzen.com/flight/base" xmlns:domestic="http://www.travelzen.com/flight/domestic" xmlns:insure="http://www.travelzen.com/flight/insure" xmlns:international="http://www.travelzen.com/flight/international" xmlns:basic="http://www.travelzen.com/flight/basic">
          <requestMetaInfo>
            <userName>'.$this->userCon['userName'].'</userName>
                    <signCode>'.$this->userCon['signCode'].'</signCode>
                    <timeStamp>'.$this->userCon['timeStamp'].'</timeStamp>
            <responseDataType>JSON</responseDataType>
          </requestMetaInfo>
          <requestEntity xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="domestic:FlightSearchV2Request">
            <domestic:flightRangeType>RT</domestic:flightRangeType>
            <domestic:flightRange>
                <domestic:fromCity>'.$data['fromCity'].'</domestic:fromCity>
                <domestic:toCity>'.$data['toCity'].'</domestic:toCity>
                <domestic:fromDate>'.$data['fromDate'].'</domestic:fromDate>
                <airCompany>'.$data['airCompany'].'</airCompany>
                <cabinRank>'.$data['cabinRank'].'</cabinRank>
              </domestic:flightRange>
                <domestic:flightRange>
              <domestic:fromCity>'.$data['toCity'].'</domestic:fromCity>
              <domestic:toCity>'.$data['fromCity'].'</domestic:toCity>
              <domestic:fromDate>'.$data['backDate'].'</domestic:fromDate>
               <airCompany>'.$data['airCompany1'].'</airCompany>
                <cabinRank>'.$data['cabinRank'].'</cabinRank>
            </domestic:flightRange>
            <domestic:cabinRank> </domestic:cabinRank>
            <domestic:checkDirect>ture</domestic:checkDirect>
            <domestic:openingTime>ture</domestic:openingTime>
            <domestic:changePNR>true</domestic:changePNR>
            <domestic:passengerNature>UNLIMITED</domestic:passengerNature>
          </requestEntity>
        </TzRequest>';
//      $res=file_get_contents('hblist.json');
    $res = $this->postXmlCurl($xml, $this->apiurl);
       Log::write("往返白屏查询线路接口数据提交:".$xml);
        Log::write("往返白屏查询线路接口:".$res);
        $res = json_decode($res, true);
        if($res['FlightSearchV2Response']['flightSegmentResult']){
            return $res['FlightSearchV2Response']['flightSegmentResult'];
        }else{
            return false;
        }
  }
    /**
     * 核价接口
     * @param $data
     */
    public function bpHejia($data){
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                        <TzRequest 
                        xmlns="http://www.travelzen.com/flight/base" 
                        xmlns:domestic="http://www.travelzen.com/flight/domestic" 
                        xmlns:insure="http://www.travelzen.com/flight/insure" 
                        xmlns:international="http://www.travelzen.com/flight/international" 
                        xmlns:basic="http://www.travelzen.com/flight/basic">
                          <requestMetaInfo>
                            <userName>'.$this->userCon['userName'].'</userName>
                            <signCode>'.$this->userCon['signCode'].'</signCode>
                            <timeStamp>'.$this->userCon['timeStamp'].'</timeStamp>
                            <responseDataType>JSON</responseDataType>
                          </requestMetaInfo>
                          <requestEntity xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="domestic:FlightGetPriceV2Request">
                            <domestic:flightInfo>
                              <domestic:flightNo>'.$data['flightNo'].'</domestic:flightNo>
                              <domestic:shareFlightNo>'.($data['shareFlightNo']?$data['realFlightNo']:false).'</domestic:shareFlightNo>
                              <domestic:cabinRank>'.$data['cabinRank'].'</domestic:cabinRank>
                              <domestic:cabinCode>'.$data['cabinCode'].'</domestic:cabinCode>
                              <domestic:subCabinCode></domestic:subCabinCode>
                              <domestic:departureDate>'.$data['departureDate'].'</domestic:departureDate>
                              <domestic:departureTime>'.$data['departureTime'].'</domestic:departureTime>
                              <domestic:arrivalDate>'.$data['arrivalDate'].'</domestic:arrivalDate>
                              <domestic:arrivalTime>'.$data['arrivalTime'].'</domestic:arrivalTime>
                              <domestic:fromAirport>'.$data['fromAirport'].'</domestic:fromAirport>
                              <domestic:toAirport>'.$data['toAirport'].'</domestic:toAirport>
                            </domestic:flightInfo>
                          </requestEntity>
                        </TzRequest>';
        $res = $this->postXmlCurl($xml, $this->apiurl);
        Log::write("核价接口数据提交:".$xml);
        Log::write("核价接口:".$res);
        $res = json_decode($res, true);
        if($res['FlightGetPriceV2Response']['flightGroupPrice']){
            return $res['FlightGetPriceV2Response']['flightGroupPrice'];
        }else{
            return false;
        }

    }

    /**
     * 白屏 下单接口
     * @param $data
     */
    public function bpAddOrder($data){
        $xml = '<TzRequest xmlns="http://www.travelzen.com/flight/base" xmlns:domestic="http://www.travelzen.com/flight/domestic" xmlns:insure="http://www.travelzen.com/flight/insure" xmlns:international="http://www.travelzen.com/flight/international" xmlns:basic="http://www.travelzen.com/flight/basic">
                      <requestMetaInfo>
                        <userName>'.$this->userCon['userName'].'</userName>
                        <signCode>'.$this->userCon['signCode'].'</signCode>
                        <timeStamp>'.$this->userCon['timeStamp'].'</timeStamp>
                        <responseDataType>JSON</responseDataType>
                      </requestMetaInfo>
                      <requestEntity xsi:type="domestic:CreateOrderCommonRequest" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
                        <domestic:externalOrderNo>'.$data['externalOrderNo'].'</domestic:externalOrderNo>
                        <domestic:orderPerson>'.$data['lxr_name'].'</domestic:orderPerson>
                        <domestic:policyId>'.$data['policyId'].'</domestic:policyId>
                        <domestic:boundPrice>'.$data['boundPrice'].'</domestic:boundPrice>
                        <domestic:facePrice>'.$data['facePrice'].'</domestic:facePrice>
                        <domestic:totalOrderPrice>'.$data['totalOrderPrice'].'</domestic:totalOrderPrice>
                        <domestic:orderSettlePrice>'.$data['orderSettlePrice'].'</domestic:orderSettlePrice>
                        <domestic:flightRangeType>'.$data['flightRangeType'].'</domestic:flightRangeType>
                        <domestic:orderEvidence/>
                        <domestic:afterTicketUrl>'.$this->callbackHost.$data['afterTicketUrl'].'</domestic:afterTicketUrl>
                        <domestic:afterRefundTicketUrl>'.$this->callbackHost.$data['afterRefundTicketUrl'].'</domestic:afterRefundTicketUrl>
                        <domestic:afterPayUrl>'.$this->callbackHost.$data['afterPayUrl'].'</domestic:afterPayUrl>
            
                        <domestic:orderFlights>
                          <domestic:flightNo>'.$data['flightNo'].'</domestic:flightNo>
                          <domestic:cabinCode>'.$data['cabinCode'].'</domestic:cabinCode>
                          <domestic:cabinType>'.$data['cabinType'].'</domestic:cabinType>
                          <domestic:fromAirport>'.$data['fromAirport'].'</domestic:fromAirport>
                          <domestic:toAirport>'.$data['toAirport'].'</domestic:toAirport>
                          <domestic:fromDate>'.$data['fromDate'].'</domestic:fromDate>
                          <domestic:toDate>'.$data['toDate'].'</domestic:toDate>
                          <domestic:planeModle>'.$data['planeModle'].'</domestic:planeModle>
                          <domestic:airportFee>'.$data['airportFee'].'</domestic:airportFee>
                          <domestic:fuelTax>'.$data['fuelTax'].'</domestic:fuelTax>
                        </domestic:orderFlights>';
                        
                        foreach ($data["passengers"] as $key => $value) {
                          $gender = (substr($value['cjrid'],-2,1) % 2 == 1)?'M':'F';
                          $xml .= '<domestic:passengers><domestic:name>'.$value['cjrname'].'</domestic:name>
                            <domestic:passengerType>'.$value['cjrfm'].'</domestic:passengerType>
                            <domestic:credentialType>'.$data['credentialType'].'</domestic:credentialType>
                            <domestic:credentialCode>'.$value['cjrid'].'</domestic:credentialCode>
                            <domestic:gender>'.$gender.'</domestic:gender>
                            <domestic:birthday>'.msubstr($value['cjrid'],6,4,'utf-8',false).'-'.msubstr($value['cjrid'],10,2,'utf-8',false).'-'.msubstr($value['cjrid'],12,2,'utf-8',false).'</domestic:birthday>
                            <domestic:phone>'.$value['cjrmobile'].'</domestic:phone></domestic:passengers>';
                        }

                        $xml .= '<domestic:contactor>
                         <domestic:name>'.$data['lxr_name'].'</domestic:name>
                          <domestic:phone>'.$data['lxr_phone'].'</domestic:phone>
                        </domestic:contactor>
                        <domestic:deliveryInfo>
                          <domestic:deliveryType>'.$data['deliveryType'].'</domestic:deliveryType>
                        </domestic:deliveryInfo>
                        <domestic:payInfo>
                          <domestic:payType/>
                          <domestic:clientIP/>
                        </domestic:payInfo>
                      </requestEntity>
                    </TzRequest>';
        $res = $this->postXmlCurl($xml, $this->apiurl);
        Log::write("白屏下单接口数据提交:".$xml);
        Log::write("白屏下单接口:".$res);
        $res = json_decode($res, true);
        $res["ct"] = $xml;
        return $res;
    }
/**
 * 往返 下单接口
 */
  public function bpwfAddOrder($data){
    $xml='<TzRequest xmlns="http://www.travelzen.com/flight/base" xmlns:domestic="http://www.travelzen.com/flight/domestic" xmlns:insure="http://www.travelzen.com/flight/insure" xmlns:international="http://www.travelzen.com/flight/international" xmlns:basic="http://www.travelzen.com/flight/basic">
        <requestMetaInfo>
          <userName>'.$this->userCon['userName'].'</userName>
                    <signCode>'.$this->userCon['signCode'].'</signCode>
                    <timeStamp>'.$this->userCon['timeStamp'].'</timeStamp>
                    <responseDataType>JSON</responseDataType>
        </requestMetaInfo>
        <requestEntity xsi:type="domestic:CreateOrderCommonRequest" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
          <domestic:externalOrderNo>'.$data['externalOrderNo'].'</domestic:externalOrderNo>
          <domestic:orderPerson>'.$data['lxr_name'].'</domestic:orderPerson>
          <domestic:policyId>'.$data['policyId'].'</domestic:policyId>
          <domestic:chdPolicyId></domestic:chdPolicyId>
          <domestic:boundPrice>'.$data['boundPrice'].'</domestic:boundPrice>
          <domestic:facePrice>'.$data['facePrice'].'</domestic:facePrice>
          <domestic:totalOrderPrice>'.$data['totalOrderPrice'].'</domestic:totalOrderPrice>
          <domestic:orderSettlePrice>'.$data['orderSettlePrice'].'</domestic:orderSettlePrice>
          <domestic:flightRangeType>'.$data['flightRangeType'].'</domestic:flightRangeType>
          <domestic:orderEvidence/>
          <domestic:afterTicketUrl>'.$this->callbackHost.$data['afterTicketUrl'].'</domestic:afterTicketUrl>
          <domestic:afterRefundTicketUrl>'.$this->callbackHost.$data['afterRefundTicketUrl'].'</domestic:afterRefundTicketUrl>
          <domestic:afterPayUrl>'.$this->callbackHost.$data['afterPayUrl'].'</domestic:afterPayUrl>
          <domestic:orderFlights>
            <domestic:flightNo>'.$data['flightNo'].'</domestic:flightNo>
            <domestic:cabinCode>'.$data['cabinCode'].'</domestic:cabinCode>
            <domestic:cabinType>'.$data['cabinType'].'</domestic:cabinType>
            <domestic:fromAirport>'.$data['fromAirport'].'</domestic:fromAirport>
            <domestic:toAirport>'.$data['toAirport'].'</domestic:toAirport>
            <domestic:fromDate>'.$data['fromDate'].'</domestic:fromDate>
            <domestic:toDate>'.$data['toDate'].'</domestic:toDate>
            <domestic:planeModle>'.$data['planeModle'].'</domestic:planeModle>
            <domestic:airportFee>'.$data['airportFee'].'</domestic:airportFee>
            <domestic:fuelTax>'.$data['fuelTax'].'</domestic:fuelTax>
          </domestic:orderFlights>
          <domestic:orderFlights>
            <domestic:flightNo>'.$data['flightNo1'].'</domestic:flightNo>
            <domestic:cabinCode>'.$data['cabinCode1'].'</domestic:cabinCode>
            <domestic:cabinType>'.$data['cabinType1'].'</domestic:cabinType>
            <domestic:fromAirport>'.$data['fromAirport1'].'</domestic:fromAirport>
            <domestic:toAirport>'.$data['toAirport1'].'</domestic:toAirport>
            <domestic:fromDate>'.$data['fromDate1'].'</domestic:fromDate>
            <domestic:toDate>'.$data['toDate1'].'</domestic:toDate>
            <domestic:planeModle>'.$data['planeModle1'].'</domestic:planeModle>
            <domestic:airportFee>'.$data['airportFee1'].'</domestic:airportFee>
            <domestic:fuelTax>'.$data['fuelTax'].'</domestic:fuelTax>
          </domestic:orderFlights>
          <domestic:passengers>
                          <domestic:name>'.$data['name'].'</domestic:name>
                          <domestic:passengerType>'.$data['passengerType'].'</domestic:passengerType>
                          <domestic:credentialType>'.$data['credentialType'].'</domestic:credentialType>
                          <domestic:credentialCode>'.$data['credentialCode'].'</domestic:credentialCode>
                          <domestic:gender>'.$data['gender'].'</domestic:gender>
                          <domestic:birthday>'.$data['birthday'].'</domestic:birthday>
                          <domestic:phone>'.$data['phone'].'</domestic:phone>
                    </domestic:passengers>
          <domestic:contactor>
            <domestic:name>'.$data['lxr_name'].'</domestic:name>
                        <domestic:phone>'.$data['lxr_phone'].'</domestic:phone>
          </domestic:contactor>
          <domestic:deliveryInfo>
            <domestic:deliveryType>'.$data['deliveryType'].'</domestic:deliveryType>
          </domestic:deliveryInfo>
          <domestic:payInfo>
            <domestic:payType/>
            <domestic:clientIP/>
          </domestic:payInfo>
        </requestEntity>
      </TzRequest>';
        $res = $this->postXmlCurl($xml, $this->apiurl);

        Log::write("往返下单接口提交数据：".$xml);
        Log::write("往返下单接口返回数据：".$res);
          $res = json_decode($res, true);
          return $res;
  }

    /**
     * 订单列表
     * @param $data
     */
    public function bpOrderList($data){
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                        <TzRequest xmlns="http://www.travelzen.com/flight/base" xmlns:domestic="http://www.travelzen.com/flight/domestic" xmlns:insure="http://www.travelzen.com/flight/insure" xmlns:international="http://www.travelzen.com/flight/international" xmlns:basic="http://www.travelzen.com/flight/basic">
                          <requestMetaInfo>
                            <userName>'.$this->userCon['userName'].'</userName>
                            <signCode>'.$this->userCon['signCode'].'</signCode>
                            <timeStamp>'.$this->userCon['timeStamp'].'</timeStamp>
                            <responseDataType>JSON</responseDataType>
                          </requestMetaInfo>
                          <requestEntity xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="domestic:OrderListQueryCommonRequest">
                            <domestic:page>'.$data['page'].'</domestic:page>
                            <domestic:orderStartDay>'.$data['orderStartDay'].'</domestic:orderStartDay>
                            <domestic:orderEndDay>'.$data['orderEndDay'].'</domestic:orderEndDay>
                            <domestic:passengerName>'.$data['passengerName'].'</domestic:passengerName>
                            <domestic:orderType>'.$data['orderType'].'</domestic:orderType>
                            <domestic:orderSubType>'.$data['orderSubType'].'</domestic:orderSubType>
                          </requestEntity>
                        </TzRequest>';
        $res = $this->postXmlCurl($xml, $this->apiurl);
        Log::write("订单列表接口数据提交:".$xml);
        Log::write("订单列表接口:".$res);
        $res = json_decode($res, true);
        return $res;

    }


    /**
     * 取消订单
     * @param $data
     */
    public function bpCancelOrder($data){
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                    <TzRequest xmlns="http://www.travelzen.com/flight/base" xmlns:domestic="http://www.travelzen.com/flight/domestic" xmlns:insure="http://www.travelzen.com/flight/insure" xmlns:international="http://www.travelzen.com/flight/international" xmlns:basic="http://www.travelzen.com/flight/basic">
                      <requestMetaInfo>
                        <userName>'.$this->userCon['userName'].'</userName>
                        <signCode>'.$this->userCon['signCode'].'</signCode>
                        <timeStamp>'.$this->userCon['timeStamp'].'</timeStamp>
                        <responseDataType>JSON</responseDataType>
                      </requestMetaInfo>
                      <requestEntity xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="domestic:CancelOrderRequest">
                        <domestic:orderID>'.$data['orderID'].'</domestic:orderID>
                        <domestic:externalOrderID>'.$data['externalOrderID'].'</domestic:externalOrderID>
                        <domestic:operator>'.$data['operator'].'</domestic:operator>
                        <domestic:cancelPNR>'.$data['cancelPNR'].'</domestic:cancelPNR>
                      </requestEntity>
                    </TzRequest>';
        $res = $this->postXmlCurl($xml, $this->apiurl);
        Log::write("取消订单接口数据提交:".$xml);
        Log::write("取消订单接口:".$res);
        $res = json_decode($res, true);
        return $res;

    }


    /**
     * 订单详情
     * @param $data
     */
    public function bpShowOrder($data){
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                    <TzRequest xmlns="http://www.travelzen.com/flight/base" xmlns:domestic="http://www.travelzen.com/flight/domestic" xmlns:insure="http://www.travelzen.com/flight/insure" xmlns:international="http://www.travelzen.com/flight/international" xmlns:basic="http://www.travelzen.com/flight/basic">
                      <requestMetaInfo>
                        <userName>'.$this->userCon['userName'].'</userName>
                        <signCode>'.$this->userCon['signCode'].'</signCode>
                        <timeStamp>'.$this->userCon['timeStamp'].'</timeStamp>
                        <responseDataType>JSON</responseDataType>
                      </requestMetaInfo>
                      <requestEntity xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="domestic:OrderDetailQueryV2Request">
                        <domestic:orderID>'.$data['orderID'].'</domestic:orderID>
                      </requestEntity>
                    </TzRequest>';
        $res = $this->postXmlCurl($xml, $this->apiurl);
        Log::write("订单详情接口数据提交:".$xml);
        Log::write("订单详情接口:".$res);
        $res = json_decode($res, true);
        return $res;
    }


    //退废单详情查询
    public function bpShowCancelOrder($data){
      $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                    <TzRequest xmlns="http://www.travelzen.com/flight/base" xmlns:domestic="http://www.travelzen.com/flight/domestic" xmlns:insure="http://www.travelzen.com/flight/insure" xmlns:international="http://www.travelzen.com/flight/international" xmlns:basic="http://www.travelzen.com/flight/basic">
                      <requestMetaInfo>
                        <userName>'.$this->userCon['userName'].'</userName>
                        <signCode>'.$this->userCon['signCode'].'</signCode>
                        <timeStamp>'.$this->userCon['timeStamp'].'</timeStamp>
                        <responseDataType>JSON</responseDataType>
                      </requestMetaInfo>
                      <requestEntity xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="domestic:RefundDetailQueryRequest">
                        <domestic:refundOrderID>'.$data['orderID'].'</domestic:refundOrderID>
                      </requestEntity>
                    </TzRequest>';
        $res = $this->postXmlCurl($xml, $this->apiurl);
        Log::write("退废单详情查询数据提交:".$xml);
        Log::write("退废单详情查询:".$res);
        $res = json_decode($res, true);
        return $res;
    }

    //改签单详情查询
    public function bpShowEndorseOrder($data){
      $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                    <TzRequest xmlns="http://www.travelzen.com/flight/base" xmlns:domestic="http://www.travelzen.com/flight/domestic" xmlns:insure="http://www.travelzen.com/flight/insure" xmlns:international="http://www.travelzen.com/flight/international" xmlns:basic="http://www.travelzen.com/flight/basic">
                      <requestMetaInfo>
                        <userName>'.$this->userCon['userName'].'</userName>
                        <signCode>'.$this->userCon['signCode'].'</signCode>
                        <timeStamp>'.$this->userCon['timeStamp'].'</timeStamp>
                        <responseDataType>JSON</responseDataType>
                      </requestMetaInfo>
                      <requestEntity xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="domestic:EndorseOrderQueryRequest">
                        <domestic:orderId>'.$data['orderID'].'</domestic:orderId>
                      </requestEntity>
                    </TzRequest>';
        $res = $this->postXmlCurl($xml, $this->apiurl);
        Log::write("改签单详情查询数据提交:".$xml);
        Log::write("改签单详情查询:".$res);
        $res = json_decode($res, true);
        return $res;
    }

    /**
     * 订单状态查询
     * @param $data
     */
    public function bpState($data){
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                    <TzRequest xmlns="http://www.travelzen.com/flight/base" xmlns:domestic="http://www.travelzen.com/flight/domestic" xmlns:insure="http://www.travelzen.com/flight/insure" xmlns:international="http://www.travelzen.com/flight/international" xmlns:basic="http://www.travelzen.com/flight/basic">
                      <requestMetaInfo>
                        <userName>'.$this->userCon['userName'].'</userName>
                        <signCode>'.$this->userCon['signCode'].'</signCode>
                        <timeStamp>'.$this->userCon['timeStamp'].'</timeStamp>
                        <responseDataType>JSON</responseDataType>
                      </requestMetaInfo>
                      <requestEntity xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="domestic:OrderStatusQueryRequest">
                        <domestic:orderID>'.$data['orderID'].'</domestic:orderID>
                      </requestEntity>
                    </TzRequest>';
        Log::write("订单状态查询请求:".$xml);
        $res = $this->postXmlCurl($xml, $this->apiurl);
        Log::write("订单状态查询:".$res);
        $res = json_decode($res, true);
        if($res['OrderStatusQueryResponse']['orderStatus'][0]['status']){
            $state = array(
                'WAIT_PAY'=>'待支付',
                'PAYING'=>'支付中',
                'WAIT_AUDIT'=>'待审核',
                'AUDIT_FALSE'=>'审核失败',
                'CANCELED'=>'已取消',
                'WAIT_ISSUE'=>'待出票',
                'PAY_FALSE'=>'支付失败',
                'ISSUE_FALSE'=>'出票失败',
                'ISSUED'=>'已出票',
                'ISSUED_SUSPEND'=>'已出票（挂起）',
                'ENDORSE_WAIT_AUDIT'=>'改签待审核',
                'ENDORSE_AUDIT_SUCCESS'=>'改签审核成功',
                'ENDORSE_AUDIT_FALSE'=>'改签审核失败',
                'WAIT_REFUND'=>'待退票',
                'REFUND_FALSE'=>'退票失败',
                'CANCEL_REFUND'=>'取消退票',
                'REFUND_FINISH'=>'退票成功',
                'REFUND_WAIT_AUDIT'=>'退票待审核',
                'REFUND_AUDIT_FALSE'=>'退票审核失败',
                'CANCEL_ENDORSE'=>'取消改签',
                'WAIT_ENDORSE'=>'待改签',
                'ENDORSE_FALSE'=>'改签失败',
                'ENDORSE_FINISH'=>'改签成功'
            );
            return array('state'=>$res['OrderStatusQueryResponse']['orderStatus'][0]['status'], 'msg'=>$state[$res['OrderStatusQueryResponse']['orderStatus'][0]['status']]);
        }else{
            return false;
        }
    }


    /**
     * 获取支付链接
     * @param $data
     */
    public function bpPayUrl($data){
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                    <TzRequest xmlns="http://www.travelzen.com/flight/base" xmlns:domestic="http://www.travelzen.com/flight/domestic" xmlns:insure="http://www.travelzen.com/flight/insure" xmlns:international="http://www.travelzen.com/flight/international" xmlns:basic="http://www.travelzen.com/flight/basic">
                      <requestMetaInfo>
                        <userName>'.$this->userCon['userName'].'</userName>
                        <signCode>'.$this->userCon['signCode'].'</signCode>
                        <timeStamp>'.$this->userCon['timeStamp'].'</timeStamp>
                        <responseDataType>JSON</responseDataType>
                      </requestMetaInfo>
                      <requestEntity xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="domestic:GetPayLinkRequest">
                        <domestic:orderID>'.$data['orderId'].'</domestic:orderID>
                        <domestic:externalOrderID>'.$data['externalOrderID'].'</domestic:externalOrderID>
                        <domestic:payType>'.$data['payType'].'</domestic:payType>
                        <domestic:operator>'.$data['operator'].'</domestic:operator>
                        <domestic:mobile>'.$data['mobile'].'</domestic:mobile>
                      </requestEntity>
                    </TzRequest>';
        $res = $this->postXmlCurl($xml, $this->apiurl);
        Log::write("获取支付链接数据提交:".$xml);
        Log::write("获取支付链接:".$res);
        $res = json_decode($res, true);   
    return $res;
    }

    /**
     * 订单支付接口
     */
    public function bpPay($data){
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                    <TzRequest xmlns="http://www.travelzen.com/flight/base" xmlns:domestic="http://www.travelzen.com/flight/domestic" xmlns:insure="http://www.travelzen.com/flight/insure" xmlns:international="http://www.travelzen.com/flight/international">
                      <requestMetaInfo>
                        <userName>'.$this->userCon['userName'].'</userName>
                        <signCode>'.$this->userCon['signCode'].'</signCode>
                        <timeStamp>'.$this->userCon['timeStamp'].'</timeStamp>
                        <responseDataType>JSON</responseDataType>
                      </requestMetaInfo>
                      <requestEntity xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="domestic:OrderPayRequest">
                        <domestic:orderID>'.$data['orderID'].'</domestic:orderID>
                        <domestic:externalOrderID>'.$data['externalOrderID'].'</domestic:externalOrderID>
                        <domestic:operator>admin</domestic:operator>
                        <domestic:afterPayUrl>'.U('Home/Jipiao/setPay', array('externalOrderNo'=>$externalOrderNo), 'html', true).'</domestic:afterPayUrl>
                        <domestic:payType>ACCOUNTPAY</domestic:payType>
                      </requestEntity>
                    </TzRequest>';
        $res = $this->postXmlCurl($xml, $this->apiurl);
        Log::write("订单支付接口数据提交:".$xml);
        Log::write("订单支付接口:".$res);
        $res = json_decode($res, true);
        return $res;

    }






    /**
     * 以post方式提交xml到对应的接口url
     *
     * @param string $xml  需要post的xml数据
     * @param string $url  url
     * @param int $second   url执行超时时间，默认40s
     * @throws WxPayException
     */
    private function postXmlCurl($xml, $url, $second = 40){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);//设置超时
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);//严格校验
        curl_setopt($ch, CURLOPT_HEADER, FALSE);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, TRUE);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $data = curl_exec($ch);//运行curl  //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            echo $error;
        }
    }


    /**
     * 获取热门城市
     */
    public function remenChengshi(){
        $model = M('jipiao_chengshi');
        $list = $model->where(array('remen'=>1))->field(true)->select();
        return $list;
    }

    /**
     * 获取全部城市
     */
      /**
     * 获取全部城市
     */
    public function chengshiAll(){
        $model = M('jipiao_chengshi');
        $list = $model->field(true)->select();
        $data = array(
            'A'=>'ABCDE',
            'B'=>'ABCDE',
            'C'=>'ABCDE',
            'D'=>'ABCDE',
            'E'=>'ABCDE',
            'F'=>'FGHIJ',
            'G'=>'FGHIJ',
            'H'=>'FGHIJ',
            'I'=>'FGHIJ',
            'J'=>'FGHIJ',
            'K'=>'KLMNO',
            'L'=>'KLMNO',
            'M'=>'KLMNO',
            'N'=>'KLMNO',
            'O'=>'KLMNO',
            'P'=>'PQRST',
            'Q'=>'PQRST',
            'R'=>'PQRST',
            'S'=>'PQRST',
            'T'=>'PQRST',
            'U'=>'UVWXYZ',
            'V'=>'UVWXYZ',
            'M'=>'UVWXYZ',
            'X'=>'UVWXYZ',
            'Y'=>'UVWXYZ',
            'Z'=>'UVWXYZ'
        );
        foreach($list as $k=>$v){
            $key = strtoupper(substr($v['pinyin'], 0, 1));
            $list[$k]['port_id'] = $data[$key];
        }
        return $list;
    }
/**
 * 获取其他
 */
    public function chengshiOther(){
        $model = M('jipiao_chengshi');
        $list = $model->where('remen<>1')->field(true)->select();
        $data = array(
            'A'=>'ABCDE',
            'B'=>'ABCDE',
            'C'=>'ABCDE',
            'D'=>'ABCDE',
            'E'=>'ABCDE',
            'F'=>'FGHIJ',
            'G'=>'FGHIJ',
            'H'=>'FGHIJ',
            'I'=>'FGHIJ',
            'J'=>'FGHIJ',
            'K'=>'KLMNO',
            'L'=>'KLMNO',
            'M'=>'KLMNO',
            'N'=>'KLMNO',
            'O'=>'KLMNO',
            'P'=>'PQRST',
            'Q'=>'PQRST',
            'R'=>'PQRST',
            'S'=>'PQRST',
            'T'=>'PQRST',
            'U'=>'UVWXYZ',
            'V'=>'UVWXYZ',
            'M'=>'UVWXYZ',
            'X'=>'UVWXYZ',
            'Y'=>'UVWXYZ',
            'Z'=>'UVWXYZ'
        );
        foreach($list as $k=>$v){
            $key = strtoupper(substr($v['pinyin'], 0, 1));
            $list[$k]['port_id'] = $data[$key];
        }
        return $list;
    }
  /**
   * 改签
   * @param $data
   */
  public function endorse($data){
    $xml='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
    <TzRequest xmlns="http://www.travelzen.com/flight/base" xmlns:domestic="http://www.travelzen.com/flight/domestic" xmlns:insure="http://www.travelzen.com/flight/insure" xmlns:international="http://www.travelzen.com/flight/international" xmlns:basic="http://www.travelzen.com/flight/basic">
      <requestMetaInfo>
        <userName>'.$this->userCon['userName'].'</userName>
            <signCode>'.$this->userCon['signCode'].'</signCode>
            <timeStamp>'.$this->userCon['timeStamp'].'</timeStamp>
        <responseDataType>JSON</responseDataType>
      </requestMetaInfo>
      <requestEntity xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="domestic:EndorseFlightOrderRequest">
        <domestic:orderId>'.$data['externalorderid'].'</domestic:orderId>
        <domestic:endorsePnr>'.$data['pnr'].'</domestic:endorsePnr>';
        
        $order = $data["order"];
        $passengernameArray = explode("|", $order["passengername"]);
        $credentialcodeArray = explode("|", $order["credentialcode"]);
        $passengertypeArray = explode("|", $order["passengertype"]);
        $ticketnoArrayTmp = explode(",", $order["ticketno"]);
        $ticketnoArray = array();
        foreach ($ticketnoArrayTmp as $key => $value) {
          $tmp = explode("|", $value);
          array_push($ticketnoArray, $tmp[1]);
        }

        for ($i=0; $i < count($passengernameArray); $i++) { 
          $xml .= '<domestic:endorseTicketInfos>
            <domestic:passengerName>'.$passengernameArray[$i].'</domestic:passengerName>
            <domestic:documentNo>'.$credentialcodeArray[$i].'</domestic:documentNo>
            <domestic:ticketNo>'.$ticketnoArray[$i].'</domestic:ticketNo>
            <domestic:passengerType>'.$passengertypeArray[$i].'</domestic:passengerType>
          </domestic:endorseTicketInfos>';
        }
        
        if(!empty($data['flightno'])){
          $xml .= '<domestic:endorseFlightSegmentInfos>
            <domestic:flightNo>'.$data['flightno'].'</domestic:flightNo>
            <domestic:origFlightNo>'.$data['origflightno'].'</domestic:origFlightNo>
            <domestic:departmentDate>'.$data['departmentdate'].'</domestic:departmentDate>
            <domestic:arrivalDate>'.$data['arrivaldate'].'</domestic:arrivalDate>
            <domestic:classCode>'.$data['classcode'].'</domestic:classCode>
          </domestic:endorseFlightSegmentInfos>';
        }
        if(!empty($data['flightno1'])){
          $xml .= 
          '<domestic:endorseFlightSegmentInfos>
          <domestic:flightNo>'.$data['flightno1'].'</domestic:flightNo>
          <domestic:origFlightNo>'.$data['origflightno1'].'</domestic:origFlightNo>
          <domestic:departmentDate>'.$data['departmentdate1'].'</domestic:departmentDate>
          <domestic:arrivalDate>'.$data['arrivaldate1'].'</domestic:arrivalDate>
          <domestic:classCode>'.$data['classcode1'].'</domestic:classCode>
        </domestic:endorseFlightSegmentInfos>';
        }
        $xml .= '<domestic:endorseUrl>'.$this->callbackHost.U('home/jipiao/endorsend',array('order'=>$data['orderid'])).'</domestic:endorseUrl>
        <domestic:endorseType>RESCHEDULE</domestic:endorseType>
      </requestEntity>
    </TzRequest>';
    $res = $this->postXmlCurl($xml, $this->apiurl);
      Log::write("改签接口数据提交:".$xml);
      Log::write("改签接口:".$res);
        $res = json_decode($res, true);
        return $res;
  }
  /**
   * 退票
   * @param $data 
   */
  public function refund($data){

    $order = $data["order"];

    $passengernameArray = explode("|", $order["passengername"]);
    $credentialcodeArray = explode("|", $order["credentialcode"]);
    $ticketnoArrayTmp = explode(",", $order["ticketno"]);
    $ticketnoArray = array();
    foreach ($ticketnoArrayTmp as $key => $value) {
      $tmp = explode("|", $value);
      array_push($ticketnoArray, $tmp[1]);
    }



    $xml='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
      <TzRequest xmlns="http://www.travelzen.com/flight/base" xmlns:domestic="http://www.travelzen.com/flight/domestic" xmlns:insure="http://www.travelzen.com/flight/insure" xmlns:international="http://www.travelzen.com/flight/international" xmlns:basic="http://www.travelzen.com/flight/basic">
        <requestMetaInfo>
            <userName>'.$this->userCon['userName'].'</userName>
                <signCode>'.$this->userCon['signCode'].'</signCode>
                <timeStamp>'.$this->userCon['timeStamp'].'</timeStamp>
            <responseDataType>JSON</responseDataType>
        </requestMetaInfo>
        <requestEntity xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
        xsi:type="domestic:RefundTicketRequest">
            <domestic:orderId>'.$data['orderid'].'</domestic:orderId>
            <domestic:externalOrderId>'.$data['externalorderid'].'</domestic:externalOrderId>
            <domestic:applyType>'.$data['applytype'].'</domestic:applyType>
            <domestic:refundReason>'.$data['refundreason'].'</domestic:refundReason>
            <domestic:pnrStatus>CANCEL</domestic:pnrStatus>
            <domestic:flightRange>'.$data['flightrange'].'</domestic:flightRange>';
            
            for ($i=0; $i < count($passengernameArray); $i++) {
              $xml .= '<domestic:refundDetail>
                <domestic:name>'.$passengernameArray[$i].'</domestic:name>
                <domestic:credentialCode>'.$credentialcodeArray[$i].'</domestic:credentialCode>
                <domestic:ticketNo>'.$ticketnoArray[$i].'</domestic:ticketNo>
              </domestic:refundDetail>';
            }

            $xml .= '<domestic:afterRefundUrl>'.$this->callbackHost.$data['afterrefundurl'].'</domestic:afterRefundUrl>
        </requestEntity>
      </TzRequest>';
    $res = $this->postXmlCurl($xml, $this->apiurl);
      Log::write("退票接口数据提交:".$xml);
      Log::write("退票接口:".$res);
        $res = json_decode($res, true);
        return $res;
  }


}