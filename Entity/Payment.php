<?php

namespace Jm\BalancedPaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Jm\BalancedPaymentBundle\Entity\Payment
 *
 * @ORM\Table("payment")
 * @ORM\Entity(repositoryClass="Jm\BalancedPaymentBundle\Entity\PaymentRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Payment
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $publicId
     *
     * @ORM\Column(name="public_id", type="string", length=255)
     */
    private $publicId;

    /**
     * @ORM\JoinColumn(name="from_user", nullable=false)
     * @ORM\ManyToOne(targetEntity="Jm\BalancedPaymentBundle\Entity\BalancedUserInterface")
     */
    private $fromUser;

    /**
     * @ORM\JoinColumn(name="to_user", nullable=true)
     * @ORM\ManyToOne(targetEntity="Jm\BalancedPaymentBundle\Entity\BalancedUserInterface")
     */
    private $toUser;

    /**
     * @var decimal $amount
     *
     * @ORM\Column(name="amount", type="decimal", scale=5, length=15)
     */
    private $amount;

    /**
     * @var string $currency
     *
     * @ORM\Column(name="currency", type="string", length=2)
     */
    private $currency;

    /**
      * @ORM\Column(name="reference", type="string", length=255, nullable=true)
      */
    private $reference;

    /**
      * @ORM\Column(name="data", type="text")
      */ 
    private $data;

    /**
      * @ORM\Column(name="state", type="string", length=10)
      */ 
    private $state;

    /**
     * @var \DateTime $updatedAt
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \DateTime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    public function __construct()
    {
        $this->currency = "DA";
        $this->setPublicId(md5(mt_rand(1, PHP_INT_MAX)));
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set publicId
     *
     * @param string $publicId
     * @return Payment
     */
    public function setPublicId($publicId)
    {
        $this->publicId = $publicId;
        return $this;
    }

    /**
     * Get publicId
     *
     * @return string
     */
    public function getPublicId()
    {
        return $this->publicId;
    }

    /**
     * Set amount
     *
     * @param float $amount
     * @return Payment
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set currency
     *
     * @param string $currency
     * @return Payment
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Get reference.
     *
     * @return reference.
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set reference.
     *
     * @param reference the value to set.
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * Get data.
     *
     * @return data.
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set data.
     *
     * @param data the value to set.
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Get state.
     *
     * @return state.
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set state.
     *
     * @param state the value to set.
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Get fromUser.
     *
     * @return fromUser.
     */
    public function getFromUser()
    {
        return $this->fromUser;
    }

    /**
     * Set fromUser.
     *
     * @param fromUser the value to set.
     */
    public function setFromUser($fromUser)
    {
        $this->fromUser = $fromUser;
        return $this;
    }

    /**
     * Get toUser.
     *
     * @return toUser.
     */
    public function getToUser()
    {
        return $this->toUser;
    }

    /**
     * Set toUser.
     *
     * @param toUser the value to set.
     */
    public function setToUser($toUser)
    {
        $this->toUser = $toUser;
        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Payment
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Payment
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
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

    public function isCredit()
    {
        return (Boolean) !$this->isDebit();
    }

    public function isDebit()
    {
        return (Boolean) null === $this->getToUser();
    }
}
