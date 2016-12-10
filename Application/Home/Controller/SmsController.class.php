<?php
/**
 * Created by PhpStorm.
 * User: litao
 * Date: 15/12/10
 * Time: 22:10
 */

namespace Home\Controller;


use Think\Controller;
use Think\Log;
use Vendor\Sms\Sms;

class SmsController extends Controller {
	public function _initialize() {
		/* 读取数据库中的配置 */
		$config = S('DB_CONFIG_DATA');
		if (!$config) {
			$config = api('Config/lists');
			S('DB_CONFIG_DATA', $config);
		}
		C($config); //添加配置
	}

	public function get_code() {

		if (IS_POST) {
			$tel    = I("post.tel");
			$v_code = I("post.v_code");

			$verify = new \Think\Verify();
			if (!$verify->check($v_code, 2)) {
				Log::write("验证码错误." . get_client_ip());
				$this->error("验证码错误");
				exit("");
				return;
			}
			$code = rand(1, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
			S("{$tel}_code", $code, 60 * 5);
			$sms      = new Sms();
			$template = "您的验证码是：{$code}。请不要把验证码泄露给其他人。如非本人操作，可不用理会！";
			$result   = $sms->send($tel, $template);
			if ($result == "发送成功！") {
				$this->success($result);
			} else {
				if (strpos($result, "4085") !== false) {
					$this->error("您发送太多次验证码,请明天再试!");
				} else {
					$this->error($sms->err);
				}

			}
		}
	}

	public function ChuPiaoSMS($tel, $fromAirport, $formAirportCode, $toAirport, $toAirportCode, $flightNo, $departureDatetime) {

		$tel    = $tel;

		$fromAirport = $fromAirport;
		$formAirportCode = $formAirportCode;
		$toAirport = $toAirport;
		$toAirportCode = $toAirportCode;
		$flightNo = $flightNo;
		$departureDatetime = $departureDatetime;

		$sms      = new Sms();
		$template = "您好，您预定的{$fromAirport}({$formAirportCode})至{$toAirport}({$toAirportCode}),{$flightNo},起飞时间{$departureDatetime},请提前2个小时去机场办理登机,祝您旅途愉快!";
		$result   = $sms->send($tel, $template);
		if ($result == "发送成功！") {
			return $result;
		} else {
			if (strpos($result, "4085") !== false) {
				return "您发送太多次验证码,请明天再试!";
			} else {
				return $sms->err;
			}

		}
	}

	public function TuiPiaoSMS($tel, $fromAirport, $formAirportCode, $toAirport, $toAirportCode, $flightNo, $departureDatetime) {

		$tel    = $tel;

		$fromAirport = $fromAirport;
		$formAirportCode = $formAirportCode;
		$toAirport = $toAirport;
		$toAirportCode = $toAirportCode;
		$flightNo = $flightNo;
		$departureDatetime = $departureDatetime;

		$sms      = new Sms();
		$template = "您好，您预定的{$fromAirport}({$formAirportCode})至{$toAirport}({$toAirportCode}),{$flightNo},起飞时间{$departureDatetime},已退票成功，欢迎再次预定!";
		$result   = $sms->send($tel, $template);
		if ($result == "发送成功！") {
			return $result;
		} else {
			if (strpos($result, "4085") !== false) {
				return "您发送太多次验证码,请明天再试!";
			} else {
				return $sms->err;
			}

		}
	}

	public function GaiQianSMS($tel, $fromAirport, $formAirportCode, $toAirport, $toAirportCode, $flightNo, $departureDatetime) {

		$tel    = $tel;

		$fromAirport = $fromAirport;
		$formAirportCode = $formAirportCode;
		$toAirport = $toAirport;
		$toAirportCode = $toAirportCode;
		$flightNo = $flightNo;
		$departureDatetime = $departureDatetime;

		$sms      = new Sms();
		$template = "您好，行程已改签成{$fromAirport}({$formAirportCode})至{$toAirport}({$toAirportCode}),{$flightNo},起飞时间{$departureDatetime},请提前2个小时去机场办理登机,祝您旅途愉快!";
		$result   = $sms->send($tel, $template);
		if ($result == "发送成功！") {
			return $result;
		} else {
			if (strpos($result, "4085") !== false) {
				return "您发送太多次验证码,请明天再试!";
			} else {
				return $sms->err;
			}

		}
	}
}