<?php

class QiwiPay implements PaymentMethod
{
    public function pay($allSum, string $phone)
    {
        echo "Заказ покупателя с телефонным номером {$phone}, на общую 
        сумму {$allSum}, оплачен с помощью сервиса Qiwi!" . "<br>";
    }
}