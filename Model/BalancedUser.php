<?php

namespace Jm\BalancedPaymentBundle\Model;

use Jm\BalancedPaymentBundle\Entity\BalancedUserInterface;

class BalancedUser implements BalancedUserInterface
{
    /**
     * @var int $id
     */
    protected $id;

    /**
     * @var string $email
     */
    protected $email;

    /**
     * @var string $balancedUri
     */
    protected $balancedUri;

    /**
     * @var string $username
     */
    protected $username;


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

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
        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }
}
