<?php

namespace Jm\BalancedPaymentBundle\Entity;

use Jm\BalancedPaymentBundle\Model\Card as BaseCard;
use Doctrine\ORM\Mapping as ORM;

/**
 * Jm\BalancedPaymentBundle\Entity\Card
 *
 * @ORM\Table("card")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Card extends BaseCard
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function getId()
    {
        return $this->id;
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
