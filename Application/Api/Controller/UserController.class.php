<?php
namespace Api\Controller;

use Think\Controller;

class UserController extends IndexController {

    protected $getUserByCardNumUrl = 'http://litao2.tunnel.joymap.cn/api.php?s=/User/getUserByCardNum.html';
    protected $auth_key = 'HTWX2016';
    protected $loginUrl = 'https://demo.tijianzhuanjia.com/redirect.html?'; //51url地址
    protected $teamInfoUrl = 'http://192.168.0.171:8009/hos/Info/getInfo'; //团检人员地址
    //http://tijianzhuanjia.jios.org:11008/hos/Info/getInfo
    //protected $teamInfoUrl = 'http://tijianzhuanjia.jios.org:11008/hos/Info/getInfo';
   
    public function _initialize() {

        //parent::_initialize();
    }

    public function getUserByCardNum() {
        $data = $this->data;
        $data = I('data');
        // $data = array(
        //  'card_num'=>array('00008555'),
        // );
        if (isset($data['card_num']) && count($data['card_num']) > 0 && count($data['card_num']) < 50) {
            
            $where = array ("card_num" => array ("IN", $data['card_num']));
            $data  = M("customer")->field("card_num,company,dept,position,is_onsite,realname,id_num,mobile,rank,status,card_num2,level,married")->where($where)->select();
            foreach ($data as &$row) {
                if (empty($row['card_num'])) {
                    $row['card_num'] = $row['card_num2'];
                }
                unset($row['card_num2']);
            }
            if ($data) {
                $this->ajaxReturn(array (
                                      "err"  => 0,
                                      "msg"  => "查询成功!",
                                      "data" => array (
                                          "user_info_list" => $data
                                      )
                                  ));
            } else {
                $this->ajaxReturn(array (
                                      "err" => 1,
                                      "msg" => "不存在!",
                                  ));
            }
        } else {
            $this->ajaxReturn(array (
                                  "err" => 1,
                                  "msg" => "请求错误!",
                              ));
        }
    }
    /*获取sign计算方式
     *Auth:lavejin
     *Date:2016年10月16日
     */
    protected function getSign($params=array(),$key='HTWX2016'){
         // /$userinfo = M('customer')->field("mobile,realname,id_num")->select();
        
       
        unset($params['sign']);
        $tarr['id_num']     = $params['id_num'];
        $tarr['mobile']     = $params['mobile'];
        $tarr['realname']   = $params['realname'];
        ksort($tarr);
        foreach ($tarr as $k => $v) {
             $str .= $k.'='.$v.'&';
        }
        $url = $str.$key;
        //var_dump($url);
        $sign = md5($url);
        return $this->loginUrl.http_build_query($params).'&sign='.$sign;
    }

     /*无缝登录
      *Auth:lavajin
      *Date:2016年10月15日
    */
    public function login51URL($target=''){
        // if (!is_login()) {
        //     $this->error("您还没有登陆", U("User/login"));
        // }
        $user = session('user_auth');
        unset($user['position']);
        unset($user['dept']);
        //unset($user['card_num']);
        unset($user['card_sn']);
        unset($user['company']);
        unset($user['is_onsite']);
        unset($user['balance']);
        unset($user['frozen_balance']);
        unset($user['status']);
        unset($user['rank']);
        unset($user['ctime']);
        unset($user['married']);
        unset($user['address']);
        unset($user['address_id']);
        unset($user['password']);
        unset($user['openid']);
        unset($user['last_expense_time']);
        unset($user['service']);
        unset($user['score']);
        //var_dump(session());
        $user = array(
            'id' => $user['card_num'],
            'realname'=>$user['realname'],
            'sex'=>$user['sex'],
            'id_num'=>$user['id_num'],
            'mobile'=>$user['mobile'],
            'source'=>$user['source'],
            'level'=>$user['level'],
            'company_code'=>$user['company_code']
        );

        // echo '<pre>';
        // var_dump($user);
        // echo '</pre>';
        // exit;
        //$userinfo = M('customer')->field("id,realname,id_num,mobile,sex,level")->select();
        //$user = $userinfo[0];
        $user['source'] = '00-30';//51验证验证医院的参数
        $user['target'] = $target; //51 验证target参数
        $url = $this->getSign($user, $this->auth_key);
        //echo $url;
        header('Location:'.$url);
        exit;
    }

    //根据身份证计算年龄
    public function getAge($ID){
        $Y = date('Y');
        $timeStack = strtotime(substr($ID,6,8));
        return date('m-d')<date('m-d',$timeStack)?$Y-date('Y',$timeStack)-1:$Y-date('Y',$timeStack);        
    }
    /*
       *获取团队人员信息
       *Auth:laravel
       *Date:2016年10月17日
    */
    public function getTeamInfo($com){
        //操作数据库
        $db = M('customer');
        $companys = $db->where(array('company'=>$com))->limit(400)->select();
        foreach ($companys as $key => $val) {
            $companys[$key]['age'] =$this->getAge($val['id_num']);
        }
        //echo $db->getLastsql();
        // select * from 'ht_customer' where(company="航天无锡疗养院")
        // SELECT * FROM `ht_customer` WHERE ( company="无锡健康通")
       // $companys =$companys[0];
        foreach($companys as $key => $val){
            unset($companys[$key]['is_onsite']);  
            unset($companys[$key]['init_balance']); 
            unset($companys[$key]['service']);   
            unset($companys[$key]['score']); 
            unset($companys[$key]['password']); 
            unset($companys[$key]['last_login_time']); 
            unset($companys[$key]['frozen_balance']); 
            unset($companys[$key]['balance']); 
            unset($companys[$key]['last_expense_time']); 
            unset($companys[$key]['address_id']); 
            unset($companys[$key]['ctime']); 
            unset($companys[$key]['position']);
            unset($companys[$key]['card_sn']);
            unset($companys[$key]['rank']);
            unset($companys[$key]['status']);
            unset($companys[$key]['married']);
            unset($companys[$key]['openid']);
            unset($companys[$key]['card_num2']);
            unset($companys[$key]['source']);        
        }
        //var
        $list = array(
            'company'=>$companys[0]['company'],
            'dwbm'   =>$companys[0]['company_code'],
            'dept'   =>$companys[0]['dept'],
            'tjzx'   =>'航天无锡疗养院',
            'teamInfo'=>$companys
        );

        //echo '['.json_encode($list).']' ;
        $url = $this->teamInfoUrl;
        $res = $this->curl_post($url,json_encode(array($list)));
     
    }
    //TODO 用户体检报告
    public function userReport() {
        $data = $this->data;
    }

    /**
     * CURL工具
     * @param 获取post
     * @return mixed
     * Auth    lavajin
     */
    public function curl_post($url, $data, $timeout_ms = 3000, $header = array()) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "server curl");
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout_ms);
        if (!empty($header) && is_array($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    
        $result = curl_exec($ch);
        $curl_info = curl_getinfo($ch);
 
        $curl_errno = curl_errno($ch);
        if($curl_errno){
            $loginfo = array(
                    'url'       => $url,
                    'data'      => $data,
                    'curl_info' => $curl_info,
                    'curl_errno'=> $curl_errno,
            );
            return false;
        }else{
            $loginfo = array(
                    'url'       => $url,
                    'curl_info' => $curl_info,
                    'result'    => $result,
            );
        }
        curl_close($ch);
        return $result;
    }
    
    
    /**
     *
     * @param string $url        请求链接
     * @param int    $timeout_ms 超时时间毫秒
     * @param array  $header     header 数组形式 例： array('Host : xxx.ezubo.com')
     * @return boolean|mixed
     */
    public function curl_get($url, $timeout_ms = 2000, $header = array(
        'Connection: Keep-Alive',
        'Content-type: application/x-www-form-urlencoded;charset=UTF-8')){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "server curl");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $timeout_ms);
        if(! empty($header) && is_array($header)){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }

        $result = curl_exec($ch);
        $curl_info = curl_getinfo($ch);
        $curl_errno = curl_errno($ch);
        
        if($curl_errno){
            $loginfo = array(
                'url'       => $url,
                'curl_info' => $curl_info,
                'curl_errno'=> $curl_errno,
            );
            return false;
        }else{
            $loginfo = array(
                'url'       => $url,
                'curl_info' => $curl_info,
                'result'    => $result,
            );
        }
        curl_close($ch);
        return $result;
    }

     
}