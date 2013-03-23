<?php

namespace Jm\BalancedPaymentBundle\Model;

use Doctrine\ORM\EntityManager;
use Jm\BalancedPaymentBundle\Entity\BalancedUserInterface;
use Jm\BalancedPaymentBundle\Entity\BalancedPayment;
use Jm\BalancedPaymentBundle\Enum\BalancedPaymentStatusEnum;
use Jm\BalancedPaymentBundle\Event\PaymentEvents;
use Jm\BalancedPaymentBundle\Event\Payment\CreditEvent;
use Jm\BalancedPaymentBundle\Event\Payment\DebitEvent;

class PaymentManager
{
    protected $balancedPayment;

    protected $logger;

    protected $user;

    protected $em;

    protected $debug;

    public function __construct($balancedPayment, $em, $logger, $dispatcher, $debug)
    {
        $this->balancedPayment = $balancedPayment;
        $this->em = $em;
        $this->logger = $logger;
        $this->dispatcher = $dispatcher;
        $this->debug = $debug;
    }

    public function createAccount(BalancedUserInterface $user)
    {
        if ($this->debug) {
            $this->logger->info(
                sprintf("[Balanced Payment] Create account for user email %s", 
                $user->getEmail())
            );
        }

        $data = $this->balancedPayment->createAccount($user->getEmail());
        $user->setBalancedUri($data->{'uri'});
        $this->em->flush();

        return true;
    }

    public function createBankAccount(BankAccount $bankAccount, $user)
    {
        if ($this->debug) {
            $this->logger->info(
                sprintf("[Balanced Payment] Create a new bank account %d", 
                $bankAccount->getId())
            );
        }

        $ba = $this->balancedPayment->createBankAccount($bankAccount);
        $data = $this->balancedPayment->attachBankAccount($ba->{'uri'}, 
            $this->getAccountUri($user));

        $bankAccount->setBalancedUri($data->{'uri'});
        $this->em->flush();

        return true;
    }

    public function deleteBankAccount(BankAccount $bankAccount)
    {
        if ($this->debug) {
            $this->logger->info(
                sprintf("[Balanced Payment] Removing bank account %d", 
                $bankAccount->getId())
            );
        }

        $data = $this->balancedPayment->deleteBankAccount($bankAccount);
        $this->em->remove($bankAccount);
        $this->em->flush();

        return true;
    }

    public function createCard(Card $card, $user)
    {
        if ($this->debug) {
            $this->logger->info(
                sprintf("[Balanced Payment] Adding card %d", 
                $card->getId())
            );
        }

        $cardData = $this->balancedPayment->createCard($card);
        $attachData = $this->balancedPayment->attachCard($cardData->{'uri'}, 
            $this->getAccountUri($user));

        $card->setBalancedUri($cardData->{'uri'});
        $this->em->flush();

        return true;
    }

    public function deleteCard(Card $card)
    {
        if ($this->debug) {
            $this->logger->info(
                sprintf("[Balanced Payment] Removing card %d", 
                $card->getId())
            );
        }

        $data = $this->balancedPayment->deleteCard($card);
        $this->em->remove($card);
        $this->em->flush();

        return true;
    }

    public function credit(BankAccount $bankAccount, $user, $amount, $reference, $description = null, $meta = null, $appearsOnStatement = null)
    {
        if ($this->debug) {
            $this->logger->info(
                sprintf("[Balanced Payment] Creating a credit of %d to %d", 
                $amount, $bankAccount->getId())
            );
        }

        $data = $this->balancedPayment->credit($bankAccount->getBalancedUri(), $amount, $description, $meta, $appearsOnStatement);

        $applicationUser = $this->em->getReference('Juiiicy\CoreBundle\Entity\User', 1); //todo: refactor

        $payment = new BalancedPayment;
        $payment
            ->setFromUser($applicationUser) // us
            ->setToUser($user)
            ->setAmount($amount)
            ->setReference($reference)
            ->setState(BalancedPaymentStatusEnum::PENDING)
            ->setData(json_encode($data))
        ;

        $this->em->persist($payment);
        $this->em->flush();

        $event = new CreditEvent($payment);
        $this->dispatcher->dispatch(PaymentEvents::CREDIT, $event);

        return true;
    }

    public function debit(Card $card, $user, $amount, $reference, $statement = null, $description = null, $meta = null)
    {
        if ($this->debug) {
            $this->logger->info(
                sprintf("[Balanced Payment] Creating a debit of %d from %d", 
                $amount, $card->getId())
            );
        }

        $ca = $this->balancedPayment->getCard($card->getBalancedUri());
        $accountUri = $this->getAccountUri($user);

        $data = $this->balancedPayment->debit($accountUri, $ca->{'uri'}, $amount, $statement, $description, $meta);

        $applicationUser = $this->em->getReference('Juiiicy\CoreBundle\Entity\User', 1); //todo: refactor

        $payment = new BalancedPayment;
        $payment
            ->setFromUser($user) 
            ->setToUser($applicationUser) //us
            ->setAmount($amount)
            ->setReference($reference)
            ->setState(BalancedPaymentStatusEnum::SUCCEEDED)
            ->setData(json_encode($data))
        ;

        $this->em->persist($payment);
        $this->em->flush();

        //dispatch event
        $event = new DebitEvent($payment);
        $this->dispatcher->dispatch(PaymentEvents::DEBIT, $event);

        return true;
    }

    public function promoteToMerchant($user)
    {
        if ($this->debug) {
            $this->logger->info(
                sprintf("[Balanced Payment] Promoting user %s to a merchant", 
                $user->getId())
            );
        }

        $accountUri = $this->getAccountUri($user);
        $account = $this->balancedPayment->getAccount($accountUri);
        $this->balancedPayment->promoteToMerchant($account, $user);

        return true;
    }

    protected function getAccountUri(BalancedUserInterface $user)
    {
        return $user->getBalancedUri();
    }
}
