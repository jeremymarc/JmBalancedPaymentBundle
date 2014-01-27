<?php

namespace Jm\BalancedPaymentBundle\Entity;

interface BalancedUserInterface
{
    public function getId();
    public function getUsername();
    public function getEmail();
    public function getBalancedUri();
    public function setBalancedUri($uri);
}
