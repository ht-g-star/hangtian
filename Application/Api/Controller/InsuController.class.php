<?php
namespace Api\Controller;

use Think\Controller;
use Think\Log;
/**
 * 保险接口API
 * @date 2016-07-21
 */

class InsuController extends Controller {
	private $userCon;
    private $apiurl;

    public function _initialize() {
        parent::_initialize();
        $time = time();
        $this->userCon = array(
            'userName'=>'57847e7845cecdb70bd730c9',
            'signCode'=>md5("tzOpenapisignCode8f3a7721timeStamp".$time."userName57847e7845cecdb70bd730c9tzOpenapi"),
            'timeStamp'=>$time
        );
        $this->apiurl = 'http://api.tdxinfo.com/service/flight/insure';
    }
    
	/**
	 * 保险产品查询
	 * @return 返回保单对象列表
	 */
	public function queryProd() {
		$xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
				<TzRequest xmlns="http://www.travelzen.com/flight/base" xmlns:domestic="http://www.travelzen.com/flight/domestic" xmlns:insure="http://www.travelzen.com/flight/insure" xmlns:international="http://www.travelzen.com/flight/international">
				  <requestMetaInfo>
				    <userName>' . $this -> userCon['userName'] . '</userName>
	                <signCode>' . $this -> userCon['signCode'] . '</signCode>
	                <timeStamp>' . $this -> userCon['timeStamp'] . '</timeStamp>
				    <requestID></requestID>
				    <responseDataType>JSON</responseDataType>
				  </requestMetaInfo>
				  <requestEntity xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="insure:ProdQueryRequest">
				    <insure:availableBusiness>DOMESTIC</insure:availableBusiness>
				  </requestEntity>
				</TzRequest>';
		$res = $this -> postXmlCurl($xml, $this -> apiurl);
		Log::write("保险产品查询请求:".$xml);
		Log::write("保险产品查询结果:".$res);
		$res = json_decode($res, true);
		if ($res['responseMetaInfo']['resultCode']==0) {
			return $res['ProdQueryResponse']['prods'];
		} else {
			return false;
		}

	}

	/**
	 * 查询保单订单
	 * @param orderid 我方订单号
	 * @param extenalOrderid 对方订单号
	 * @param 保单ID
	 * @return 返回订单详情
	 */
	public function queryOrder($orderid, $extenalOrderid, $insureid) {
		$xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
			<TzRequest xmlns="http://www.travelzen.com/flight/base" xmlns:domestic="http://www.travelzen.com/flight/domestic" xmlns:insure="http://www.travelzen.com/flight/insure" xmlns:international="http://www.travelzen.com/flight/international">
			  <requestMetaInfo>
			  	<userName>' . $this -> userCon['userName'] . '</userName>
                <signCode>' . $this -> userCon['signCode'] . '</signCode>
                <timeStamp>' . $this -> userCon['timeStamp'] . '</timeStamp>
			    <requestID></requestID>
			    <responseDataType>JSON</responseDataType>
			  </requestMetaInfo>
			  <requestEntity xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="insure:QueryRequest">
			    <insure:orderId>' . $extenalOrderid . '</insure:orderId>
			    <insure:extenalOrderId>' .$orderid  . '</insure:extenalOrderId>
			    <insure:insureId>' . $insureid . '</insure:insureId>
			  </requestEntity>
			</TzRequest>';
		$res = $this -> postXmlCurl($xml, $this -> apiurl);
		Log::write("查询保单订单请求:".$xml);
		Log::write("查询保单订单结果:".$res);
		$res = json_decode($res, true);
		if ($res['FlightSearchV2Response']['flightSegmentResult']) {
			return $res['FlightSearchV2Response']['flightSegmentResult'];
		} else {
			return false;
		}
	}

	/**
	 * 建单
	 * @param pid 保险产品ID
	 * @param oid 机票订单号
	 * @param faceprice 保险面价
	 * @param afterPayUrl 支付回调地址
	 * @param applySuccessUrl 下单成功回调地址
	 * @param buynum 购买数量
	 * @return 返回对方订单号
	 */
	public function createOrder($pid,$oid,$faceprice,$afterPayUrl,$applySuccessUrl,$buynum,$isover18 = 1,$toubaoren = array(), $oldoid = "") {
		$order=M('jipiao_order')->where(array('orderid'=>$oid, "isactive"=>1))->find();
		if(empty($order)){
			$order=M('jipiao_order')->where(array('orderid'=>$oldoid, "isactive"=>1))->find();
		}

		$passengernameArray = explode("|", $order["passengername"]);
		$credentialcodeArray = explode("|", $order["credentialcode"]);
		$shengriArray = explode("|", $order["shengri"]);


		for ($i = 0; $i < count($credentialcodeArray); $i++) {
			$age = self::returnAge($shengriArray[$i]);
			if($age > 18){
				$gender = (substr($credentialcodeArray[$i],-2,1) % 2 == 1)?'MALE':'FEMALE';
				$toubaoren = array(
						"name" => $passengernameArray[$i],
						"id" => $credentialcodeArray[$i],
						"phone" => $order['lxr_phone'],
						"shengri" => $shengriArray[$i],
						"gender" => $gender
					);
				break;
			}
		}

		$xml='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
			<TzRequest xmlns="http://www.travelzen.com/flight/base" xmlns:domestic="http://www.travelzen.com/flight/domestic" xmlns:insure="http://www.travelzen.com/flight/insure" xmlns:international="http://www.travelzen.com/flight/international">
			  <requestMetaInfo>
			   	<userName>' . $this -> userCon['userName'] . '</userName>
                <signCode>' . $this -> userCon['signCode'] . '</signCode>
                <timeStamp>' . $this -> userCon['timeStamp'] . '</timeStamp>
			    <requestID></requestID>
			    <responseDataType>JSON</responseDataType>
			  </requestMetaInfo>
			  <requestEntity xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="insure:UnderWriteRequest">
			    <insure:extenalOrderId>'.$oid.'</insure:extenalOrderId>
			    <insure:prodInfo>
			      <insure:id>'.$pid.'</insure:id>
			      <insure:price>'.$faceprice.'</insure:price>
			    </insure:prodInfo>
			    <insure:flightInfo>
			      <insure:firstTripNo>'.$order['flightno'].'</insure:firstTripNo>
			      <insure:firstTripTakeOffTime>'.$order['departuredate'].' '.$order['departuretime'].':00</insure:firstTripTakeOffTime>
			    </insure:flightInfo>';
			    for ($i=0; $i < count($passengernameArray); $i++) { 
			    	$gender = (substr($credentialcodeArray[$i],-2,1) % 2 == 1)?'MALE':'FEMALE';
				    $xml .= '<insure:underWritePersons>
			    	<insure:name>'.$toubaoren['name'].'</insure:name>
					     <insure:gender>'.$toubaoren['gender'].'</insure:gender>
					      <insure:birthday>'.$toubaoren["shengri"].'</insure:birthday>
					      <insure:credentialIdType>IDENTIFY</insure:credentialIdType>
					      <insure:credentialId>'.$toubaoren['id'].'</insure:credentialId>
					      <insure:phone>'.$toubaoren['phone'].'</insure:phone>
					      <insure:insuredInfo>
					        <insure:name>'.$passengernameArray[$i].'</insure:name>
					        <insure:gender>'.$gender.'</insure:gender>
					        <insure:birthday>'.$shengriArray[$i].'</insure:birthday>
					        <insure:credentialIdType>IDENTIFY</insure:credentialIdType>
					        <insure:credentialId>'.$credentialcodeArray[$i].'</insure:credentialId>
					        <insure:count>'.$buynum.'</insure:count>
					        <insure:phone>'.$order['lxr_phone'].'</insure:phone>
					      </insure:insuredInfo>
				    </insure:underWritePersons>';
			    }

		   $xml .= '</requestEntity>
		</TzRequest>';
		$res = $this -> postXmlCurl($xml, $this -> apiurl);
		Log::write("保险建单请求:".$xml);
		Log::write("保险建单结果:".$res);
		$res = json_decode($res, true);
		if ($res['responseMetaInfo']['resultCode']==0) {
			return $res['UnderWriteResponse']['orderId'];
		} else {
			return false;
		}
		
	}


	public function returnAge($mydate){
	    $birth = $mydate; 
	    list($by,$bm,$bd) = explode('-',$birth); 
	    $cm = date('n'); 
	    $cd = date('j'); 
	    $age = date('Y')-$by-1; 
	    if ($cm > $bm || $cm == $bm && $cd > $bd) $age++; 
	    return $age; 
	}

	public function createOrderType2($pid,$oid,$faceprice,$afterPayUrl,$applySuccessUrl,$buynum,$isover18 = 1,$toubaoren = array(), $oldoid = "") {
		$order=M('jipiao_order')->where(array('orderid'=>$oid, "isactive"=>1))->find();
		if(empty($order)){
			$order=M('jipiao_order')->where(array('orderid'=>$oldoid, "isactive"=>1))->find();
		}

		$passengernameArray = explode("|", $order["passengername"]);
		$credentialcodeArray = explode("|", $order["credentialcode"]);
		$shengriArray = explode("|", $order["shengri"]);


		for ($i = 0; $i < count($credentialcodeArray); $i++) {
			$age = self::returnAge($shengriArray[$i]);
			if($age > 18){
				$gender = (substr($credentialcodeArray[$i],-2,1) % 2 == 1)?'MALE':'FEMALE';
				$toubaoren = array(
						"name" => $passengernameArray[$i],
						"id" => $credentialcodeArray[$i],
						"phone" => $order['lxr_phone'],
						"shengri" => $shengriArray[$i],
						"gender" => $gender
					);
				break;
			}
		}

		
		
		$xml='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
			<TzRequest xmlns="http://www.travelzen.com/flight/base" xmlns:domestic="http://www.travelzen.com/flight/domestic" xmlns:insure="http://www.travelzen.com/flight/insure" xmlns:international="http://www.travelzen.com/flight/international">
			  <requestMetaInfo>
			   	<userName>' . $this -> userCon['userName'] . '</userName>
                <signCode>' . $this -> userCon['signCode'] . '</signCode>
                <timeStamp>' . $this -> userCon['timeStamp'] . '</timeStamp>
			    <requestID></requestID>
			    <responseDataType>JSON</responseDataType>
			  </requestMetaInfo>
			  <requestEntity xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="insure:UnderWriteRequest">
			    <insure:extenalOrderId>'.$oid.'</insure:extenalOrderId>
			    <insure:prodInfo>
			      <insure:id>'.$pid.'</insure:id>
			      <insure:price>'.$faceprice.'</insure:price>
			    </insure:prodInfo>
			    <insure:flightInfo>
			      <insure:firstTripNo>'.$order['flightno'].'</insure:firstTripNo>
			      <insure:firstTripTakeOffTime>'.$order['departuredate'].' '.$order['departuretime'].':00</insure:firstTripTakeOffTime>
			    </insure:flightInfo>';

			    for ($i=0; $i < count($passengernameArray); $i++) { 
			    	$gender = (substr($credentialcodeArray[$i],-2,1) % 2 == 1)?'MALE':'FEMALE';
				    $xml .= '<insure:underWritePersons>
			    	<insure:name>'.$toubaoren['name'].'</insure:name>
					     <insure:gender>'.$toubaoren["gender"].'</insure:gender>
					      <insure:birthday>'.$toubaoren["shengri"].'</insure:birthday>
					      <insure:credentialIdType>IDENTIFY</insure:credentialIdType>
					      <insure:credentialId>'.$toubaoren['id'].'</insure:credentialId>
					      <insure:phone>'.$toubaoren['phone'].'</insure:phone>
					      <insure:insuredInfo>
					        <insure:name>'.$passengernameArray[$i].'</insure:name>
					        <insure:gender>'.$gender.'</insure:gender>
					        <insure:birthday>'.$shengriArray[$i].'</insure:birthday>
					        <insure:credentialIdType>IDENTIFY</insure:credentialIdType>
					        <insure:credentialId>'.$credentialcodeArray[$i].'</insure:credentialId>
					        <insure:count>'.$buynum.'</insure:count>
					        <insure:phone>'.$order['lxr_phone'].'</insure:phone>
					      </insure:insuredInfo>
				    </insure:underWritePersons>';
			    }

		    $xml .= '</requestEntity>
		</TzRequest>';
		$res = $this -> postXmlCurl($xml, $this -> apiurl);
		Log::write("保险建单请求:".$xml);
		Log::write("保险建单结果:".$res);
		$res = json_decode($res, true);
		return $res;
		
	}
	/**
	 * 支付保单订单
	 * @param $orderid 我方订单
	 * @param $extenalOrderId 对方订单
	 * @param afterPayUrl 支付回调地址
	 * @param applySuccessUrl 下单成功回调地址
	 */
	public function payOrder($orderid,$extenalOrderId,$afterPayUrl,$applySuccessUrl) {
		$xml='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
			<TzRequest xmlns="http://www.travelzen.com/flight/base" xmlns:domestic="http://www.travelzen.com/flight/domestic" xmlns:insure="http://www.travelzen.com/flight/insure" xmlns:international="http://www.travelzen.com/flight/international">
			  <requestMetaInfo>
			    <userName>' . $this -> userCon['userName'] . '</userName>
                <signCode>' . $this -> userCon['signCode'] . '</signCode>
                <timeStamp>' . $this -> userCon['timeStamp'] . '</timeStamp>
			    <requestID></requestID>
			    <responseDataType>JSON</responseDataType>
			  </requestMetaInfo>
			  <requestEntity xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="insure:PayRequest">
			    <insure:orderId>'.$extenalOrderId.'</insure:orderId>
			    <insure:extenalOrderId>'.$orderid.'</insure:extenalOrderId>
			    <insure:payInfo>
			      <insure:type>ACCOUNTPAY</insure:type>
			      <insure:afterPayUrl>http://'.$_SERVER['HTTP_HOST'].$afterPayUrl.'</insure:afterPayUrl>
			      <insure:applySuccessUrl>http://'.$_SERVER['HTTP_HOST'].$applySuccessUrl.'</insure:applySuccessUrl>
			      <insure:remark>下单支付</insure:remark>
			    </insure:payInfo>
			  </requestEntity>
			</TzRequest>';
		$res = $this -> postXmlCurl($xml, $this -> apiurl);
		Log::write("支付保单接口请求：".$xml);
		Log::write("支付保单接口数据：".$res);
		$res = json_decode($res, true);
		if ($res['responseMetaInfo']['resultCode']==0) {
			return $res['PayResponse'];
		} else {
			return false;
		}
	}
	/**
	 * 取消订单
	 * 支付前
	 * @param $orderId 我方订单号
	 * @param $extenalOrderId 对方订单号
	 * @param $reason 取消理由
	 */
	public function cancelOrder($orderId,$extenalOrderId,$reason) {
		$xml='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
		<TzRequest xmlns="http://www.travelzen.com/flight/base" xmlns:domestic="http://www.travelzen.com/flight/domestic" xmlns:insure="http://www.travelzen.com/flight/insure" xmlns:international="http://www.travelzen.com/flight/international">
		  <requestMetaInfo>
		   	<userName>' . $this -> userCon['userName'] . '</userName>
            <signCode>' . $this -> userCon['signCode'] . '</signCode>
            <timeStamp>' . $this -> userCon['timeStamp'] . '</timeStamp>
		    <requestID></requestID>
		    <responseDataType>JSON</responseDataType>
		  </requestMetaInfo>
		  <requestEntity xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="insure:CancelRequest">
		    <insure:orderId>'.$extenalOrderId.'</insure:orderId>
		    <insure:extenalOrderId>'.$orderId.'</insure:extenalOrderId>
		    <insure:reason>'.$reason.'</insure:reason>
		  </requestEntity>
		</TzRequest>';
		$res = $this -> postXmlCurl($xml, $this -> apiurl);
		Log::write("取消保单请求:".$xml);
		Log::write("取消保单结果:".$res);
		$res = json_decode($res, true);
		if ($res['responseMetaInfo']['resultCode']==0) {
			return $res['CancelResponse'];
		} else {
			return false;
		}
	}
	/**
	 * 关闭订单
	 * 支付后
	 * @param $orderId 我方订单号
	 * @param $extenalOrderId 对方订单号
	 * @param $reason 取消理由
	 * @param $name
	 * @param $insureId
	 * @param $afterAbortUrl
	 */
	public function closeOrder($orderId,$extenalOrderId,$reason,$nameArr,$insureIdArr,$afterAbortUrl) {
		$xml='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
			<TzRequest xmlns="http://www.travelzen.com/flight/base" xmlns:domestic="http://www.travelzen.com/flight/domestic" xmlns:insure="http://www.travelzen.com/flight/insure" xmlns:international="http://www.travelzen.com/flight/international">
			  <requestMetaInfo>
			    	<userName>' . $this -> userCon['userName'] . '</userName>
		            <signCode>' . $this -> userCon['signCode'] . '</signCode>
		            <timeStamp>' . $this -> userCon['timeStamp'] . '</timeStamp>
				    <requestID></requestID>
				    <responseDataType>JSON</responseDataType>
			  </requestMetaInfo>
			  <requestEntity xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="insure:AbortRequest">
			    <insure:orderId>'.$extenalOrderId.'</insure:orderId>
			    <insure:extenalOrderId>'.$orderId.'</insure:extenalOrderId>
			    <insure:reason>'.$reason.'</insure:reason>';
			    for ($i=0; $i < count($nameArr); $i++) { 
			    	$xml .= '<insure:abortPerson>
				      <insure:name>'.$nameArr[$i].'</insure:name>
				      <insure:insureId>'.$insureIdArr[$i].'</insure:insureId>
				    </insure:abortPerson>';
			    }
			    

			    $xml .= '<insure:afterAbortUrl>http://'.$_SERVER['HTTP_HOST'].$afterAbortUrl.'</insure:afterAbortUrl>
			  </requestEntity>
			</TzRequest>';
		$res = $this -> postXmlCurl($xml, $this -> apiurl);
		Log::write('退保后，取消订单请求：'.$xml);
		Log::write('退保后，取消订单返回数据：'.$res);
		$res = json_decode($res, true);
		if ($res['responseMetaInfo']['resultCode']==0) {
			return $res['AbortResponse'];
		} else {
			return false;
		}
	}
	/**
	 * 以post方式提交xml到对应的接口url
	 *
	 * @param string $xml  需要post的xml数据
	 * @param string $url  url
	 * @param int $second   url执行超时时间，默认40s
	 * @throws WxPayException
	 */
	private function postXmlCurl($xml, $url, $second = 40) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT, $second);
		//设置超时
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		//严格校验
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		//设置header
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		//要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_POST, TRUE);
		//post提交方式
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		$data = curl_exec($ch);
		//运行curl  //返回结果
		if ($data) {
			curl_close($ch);
			return $data;
		} else {
			$error = curl_errno($ch);
			curl_close($ch);
			echo $error;
		}
	}

}
