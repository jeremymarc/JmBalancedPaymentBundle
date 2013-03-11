<?php

namespace Jm\BalancedPaymentBundle\Event\Payment;

use Symfony\Component\EventDispatcher\Event;
use Jm\BalancedPaymentBundle\Entity\Payment;

class DebitEvent extends Event
{
    private $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function getPayment()
    {
        return $this->payment;
    }
}
