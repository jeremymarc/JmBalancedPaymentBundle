<?php

namespace Jm\BalancedPaymentBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * BalancedPayment\Model\PaymentSource
 */
class PaymentSource
{

    protected $id;

    /**
     * @var boolean $enabled
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled;

    /**
     * @ORM\ManyToOne(targetEntity="Union\CoreBundle\Entity\User")
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
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
