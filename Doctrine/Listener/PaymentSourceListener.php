<?php

namespace Jm\BalancedPaymentBundle\Doctrine\Listener;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use Jm\BalancedPaymentBundle\Model\PaymentManager;
use Jm\BalancedPaymentBundle\Entity\BalancedUserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Jm\BalancedPaymentBundle\Entity\Card;
use Jm\BalancedPaymentBundle\Entity\BankAccount;

class PaymentSourceListener
{
    private $manager;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {
    }

    public function prePersist(LifecycleEventArgs $eventArgs)
    {

        if ($eventArgs->getEntity() instanceof BalancedUserInterface) {
            if (!$this->getManager()->createAccount($eventArgs->getEntity())) {
                throw new \Exception('A problem occured trying creating the User.');
            }
        }

        if ($eventArgs->getEntity() instanceof Card) {
            if (!$this->getManager()->createCard($eventArgs->getEntity(), $this->getUser())) {
                throw new \Exception('A problem occured trying creating the Card.');
            }
        }

        if ($eventArgs->getEntity() instanceof BankAccount) {
            if (!$this->getManager()->createBankAccount($eventArgs->getEntity(), $this->getUser())) {
                throw new \Exception('A problem occured trying creating the BankAccount.');
            }
        }
    }

    private function getManager()
    {
        return $this->container->get('jm_balancedpayment.payment.manager');
    }

    private function getUser()
    {
        $securityContext = $this->container->get('security.context');
        return $securityContext->getToken()->getUser();
    }
}
