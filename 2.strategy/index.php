<?php

spl_autoload_register(function ($classname) {
	require_once ($classname.'.php');
});


// TODO доделай реализацию остальных методово оплаты 

$order1 = new Order(1200, "+79845123146");
$order2 = new Order(2900, "+79004564568");
$order3 = new Order(150, "+78005050555");


$order1->ordered(new QiwiPay);
$order2->ordered(new WebMoneyPay);
$order3->ordered(new YandexPay);