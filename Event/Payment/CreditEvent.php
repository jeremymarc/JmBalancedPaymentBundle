<?php

namespace Jm\BalancedPaymentBundle\Event\Payment;

use Symfony\Component\EventDispatcher\Event;
use Jm\BalancedPaymentBundle\Entity\BalancedPayment;

class CreditEvent extends Event
{
    private $payment;

    public function __construct(BalancedPayment $payment)
    {
        $this->payment = $payment;
    }

    public function getPayment()
    {
        return $this->payment;
    }
}
