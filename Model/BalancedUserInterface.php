<?php

namespace Jm\BalancedPaymentBundle\Model;

interface BalancedUserInterface
{
    public function getBalancedUri();
    public function setBalancedUri($uri);
}
