<?php
/**
 * author: yutao
 * createTime: 2018/9/7 下午11:13
 * description:
 */

use yutao8\BankRegion\Tui78;

include 'vendor/autoload.php';
$obj = new Tui78();
$t1 = time();
$data['cate'] = $obj->cate_list();
foreach($data['cate'] as $bank){
	if(isset($bank['id']) && isset($bank['url'])){
		print_r($bank['title'] . PHP_EOL);
		$data['city'][$bank['id']] = $obj->city_list($bank['url']);
		foreach($data['city'][$bank['id']] as $city_list){
			if($city_list['city']){
				foreach($city_list['city'] as $city){
					print_r('       ' . $city['title'] . PHP_EOL);
					$data['bank'][$bank['id']][] = $obj->bank_list($city['url']);
				}
			}
		}
	}
}
$t2 = time();
echo $t2 - $t1;
file_put_contents('data2.json',json_encode($data,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
//echo json_encode($data);
