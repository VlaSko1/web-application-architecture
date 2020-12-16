<?php

class YandexPay implements PaymentMethod
{
    public function pay($allSum, string $phone)
    {
        echo "Заказ покупателя с телефонным номером {$phone}, на общую 
        сумму {$allSum}, оплачен с помощью сервиса Yandex!" . "<br>";
    }
}