<?php

class WebMoneyPay implements PaymentMethod
{
    public function pay($allSum, string $phone)
    {
        echo "Заказ покупателя с телефонным номером {$phone}, на общую 
        сумму {$allSum}, оплачен с помощью сервиса WebMoney!" . "<br>";
    }
}