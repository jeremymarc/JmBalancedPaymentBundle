<?php

namespace Jm\BalancedPaymentBundle\Model;

class BalancedUser implements BalancedUserInterface
{
    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    protected $email;

    /**
     * @var string $balancedUri
     *
     * @ORM\Column(name="balanced_uri", type="string", length=255)
     */
    protected $balancedUri;

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
}
