<?php

namespace Jm\BalancedPaymentBundle\Model;

class BalancedUser implements BalancedUserInterface
{
    protected $balancedUri;
    protected $email;

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getBalancedUri()
    {
        return $balancedUri;
    }

    public function setBalancedUri($uri)
    {
        $this->balancedUri = $uri;
    }
}
