<?php

class Order
{
    public function __construct($allSum, string $phone)
    {
        $this->allSum = $allSum;
        $this->phone = $phone;
    }

    public function ordered(PaymentMethod $method) 
    {
        $method->pay($this->allSum, $this->phone);
    }
}