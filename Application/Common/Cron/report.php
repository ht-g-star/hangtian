<?php
/**
 * Created by PhpStorm.
 * User: litao
 * Date: 16/2/1
 * Time: 12:29
 */
//体检报告整理
//\Think\Log::write("准备开始整理体检报告..");
$active = 0;
$cmh    = curl_multi_init();
$ch1    = curl_init();
$ch2    = curl_init();
curl_setopt($ch1, CURLOPT_URL, "http://127.0.0.1/report.php");
curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, 1);          
curl_setopt($ch2, CURLOPT_URL, "http://127.0.0.1/admin.php?s=/Public/express.html");

curl_multi_add_handle($cmh, $ch1);
curl_multi_add_handle($cmh, $ch2);
curl_multi_exec($cmh, $active);