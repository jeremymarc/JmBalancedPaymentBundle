<?php

namespace Jm\BalancedPaymentBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

interface BalancedUserInterface extends BaseUserInterface
{
    public function getId();
    public function getBalancedUri();
    public function setBalancedUri($uri);
}
