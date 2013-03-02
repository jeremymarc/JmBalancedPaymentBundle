<?php

namespace Jm\BalancedPaymentBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Jm\BalancedPaymentBundle\Model\Card
 */
class Card extends PaymentSource
{
    /**
     * @var string $number
     *
     * @ORM\Column(name="number", type="string", length=16)
     */
    protected $number;

    /**
     * @var string $routingNumber
     *
     * @ORM\Column(name="cvv", type="string", length=4)
     */
    protected $cvv;

    /**
     * @var number $expirationMonth
     *
     * @ORM\Column(name="exp_month", type="integer", length=2)
     */
    protected $expirationMonth;

    /**
     * @var number $expirationYear
     *
     * @ORM\Column(name="exp_year", type="integer", length=2)
     */
    protected $expirationYear;

    /**
     * Get card number.
     *
     * @return number.
     */
    public function getNumber()
    {
        return $this->number;
    }
    
    /**
     * Set card number.
     *
     * @param Number the value to set.
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * Set cvv
     *
     * @param string $cvv
     * @return CreditCard
     */
    public function setCvv($cvv)
    {
        $this->cvv = $cvv;
        return $this;
    }

    /**
     * Get cvv
     *
     * @return string
     */
    public function getCvv()
    {
        return $this->cvv;
    }
    
    /**
     * Get expirationMonth.
     *
     * @return expirationMonth.
     */
    public function getExpirationMonth()
    {
        return $this->expirationMonth;
    }
    
    /**
     * Set expirationMonth.
     *
     * @param expirationMonth the value to set.
     */
    public function setExpirationMonth($expirationMonth)
    {
        $this->expirationMonth = $expirationMonth;
    }
    
    /**
     * Get expirationYear.
     *
     * @return expirationYear.
     */
    public function getExpirationYear()
    {
        return $this->expirationYear;
    }
    
    /**
     * Set expirationYear.
     *
     * @param expirationYear the value to set.
     */
    public function setExpirationYear($expirationYear)
    {
        $this->expirationYear = $expirationYear;
    }
}
