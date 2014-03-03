<?php

namespace Jm\BalancedPaymentBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Jm\BalancedPaymentBundle\Model\BankAccount
 */
abstract class BankAccount
{
    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=100)
     * @Assert\NotNull
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="Jm\BalancedPaymentBundle\Entity\BalancedUserInterface")
     */
    protected $owner;

    /**
     * @var string $accountNumber
     *
     * @ORM\Column(name="account_number", type="string", length=20)
     * @Assert\NotNull
     */
    protected $accountNumber;

    /**
     * @var string $routingNumber
     *
     * @ORM\Column(name="routing_number", type="string", length=9)
     * @Assert\NotNull
     * @Assert\Length(min = "9", max = "9")
     */
    protected $routingNumber;

    /**
     * @var integer $type
     *
     * @ORM\Column(name="type", type="string")
     * @Assert\Choice(choices={"CHECKING", "SAVINGS"})
     */
    protected $type;

    /**
     * @var boolean $enabled
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled;

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
     * Get name.
     *
     * @return name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @param name the value to set.
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
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
     * @param  string      $routingNumber
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
     * @param  integer     $type
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
}
