<?php

interface PaymentMethod
{
    public function pay($allSum, string $phone);
}