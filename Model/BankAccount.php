<?php

namespace Jm\BalancedPaymentBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Jm\BalancedPaymentBundle\Model\BankAccount
 */
class BankAccount extends PaymentSource
{
    /**
     * @var string $accountNumber
     *
     * @ORM\Column(name="account_number", type="string", length=10)
     */
    private $accountNumber;

    /**
     * @var string $routingNumber
     *
     * @ORM\Column(name="routing_number", type="string", length=10)
     */
    private $routingNumber;

    /**
     * @var integer $type
     *
     * @ORM\Column(name="type", type="string", 
     * columnDefinition="enum('checking', 'savings')")
     */
    private $type;

    /**
     * Get accountNumber.
     *
     * @return accountNumber.
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }
    
    /**
     * Set accountNumber.
     *
     * @param accountNumber the value to set.
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;
        return $this;
    }

    /**
     * Set routingNumber
     *
     * @param string $routingNumber
     * @return BankAccount
     */
    public function setRoutingNumber($routingNumber)
    {
        $this->routingNumber = $routingNumber;
        return $this;
    }

    /**
     * Get routingNumber
     *
     * @return string 
     */
    public function getRoutingNumber()
    {
        return $this->routingNumber;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return BankAccount
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }
}
