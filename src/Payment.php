<?php

namespace DevGroup\Dreamkas;


/**
 * Class Payment
 */
class Payment extends Configurable
{
    public $sum = 0;

    public $type = PaymentType::TYPE_CASHLESS;
}