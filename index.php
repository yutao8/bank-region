<?php

use yutao8\BankRegion\Bank;
use yutao8\BankRegion\City;

include 'vendor/autoload.php';
$city_obj = new City();
$bank_obj = new Bank;
$t1 = time();
$data['bank_cate'] = $bank_obj->cate();
$data['province'] = $city_obj->province();
foreach($data['province'] as $province){
	$data['city'][$province['id']] = $city_obj->city($province['id']);
}
if($data['bank_cate'] && $data['province'] && $data['city']){
	foreach($data['bank_cate'] as $bank_cate){
		print_r($bank_cate['title'] . PHP_EOL);
		foreach($data['province'] as $province){
			print_r('   ' . $province['title'] . PHP_EOL);
			foreach($data['city'][$province['id']] as $city){
				print_r('       ' . $city['name'] . PHP_EOL);
				$data['bank'][$bank_cate['id']][$province['id']][$city['id']] = $bank_obj->all($bank_cate['id'],$province['id'],$city['id']);
			}
		}
	}
}
$t2 = time();
echo $t2 - $t1;
file_put_contents('data.json',json_encode($data,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
//echo json_encode($data);

