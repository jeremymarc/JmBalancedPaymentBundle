<?php

namespace Jm\BalancedPaymentBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Jm\BalancedPaymentBundle\Model\Card
 */
class Card
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
     * @var boolean $enabled
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled;

    /**
     * @ORM\ManyToOne(targetEntity="Jm\BalancedPaymentBundle\Entity\BalancedUserInterface")
     */
    protected $owner;

    /**
     * @var string $balancedUri
     *
     * @ORM\Column(name="balanced_uri", type="string", length=255)
     */
    protected $balancedUri;

    /**
     * @var \DateTime $updatedAt
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @var \DateTime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

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
     * @param  string     $cvv
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

    /**
     * Set enabled
     *
     * @param  boolean       $enabled
     * @return PaymentSource
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set owner.
     *
     * @param owner the value to set.
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner.
     *
     * @return owner.
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set balancedUri.
     *
     * @param balancedUri the value to set.
     */
    public function setBalancedUri($balancedUri)
    {
        $this->balancedUri = $balancedUri;

        return $this;
    }

    /**
     * Get balancedUri.
     *
     * @return balancedUri.
     */
    public function getBalancedUri()
    {
        return $this->balancedUri;
    }

    /**
     * Get updatedAt.
     *
     * @return updatedAt.
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set updatedAt.
     *
     * @param updatedAt the value to set.
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return createdAt.
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set createdAt.
     *
     * @param createdAt the value to set.
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function beforePersist()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @ORM\PreUpdate
     */
    public function beforeUpdate()
    {
        $this->setUpdatedAt(new \DateTime());
    }
}
