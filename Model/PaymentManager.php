<?php

namespace Jm\BalancedPaymentBundle\Model;

use Union\PaymentBundle\balancedPayment\PaymentbalancedPaymentFactory;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityManager;

class PaymentManager
{
    protected $balancedPayment;

    protected $logger;

    protected $user;

    protected $em;

    protected $debug;

    public function __construct($balancedPayment, SecurityContext $context, $em, $logger, $debug)
    {
        $this->balancedPayment = $balancedPayment;
        $this->em = $em;
        $this->logger = $logger;
        $this->debug = $debug;

        if ($context->getToken()) {
            $this->user = $context->getToken()->getUser();
        }
    }

    public function createAccount(BalancedUser $user)
    {
        if ($this->debug) {
            $this->logger->info(
                printf("[Balanced Payment] Create account for user %d", 
                $user->getId())
            );
        }

        $data = $this->balancedPayment->createAccount($user->getEmail());
        $user->setBalancedUri($data->{'uri'});
        $this->em->flush();

        return true;
    }

    public function createBankAccount(BankAccount $bankAccount)
    {
        if ($this->debug) {
            $this->logger->info(
                printf("[Balanced Payment] Create a new bank account %d", 
                $bankAccount->getId())
            );
        }

        $ba = $this->balancedPayment->createBankAccount($bankAccount);
        $data = $this->balancedPayment->attachBankAccount($ba->{'uri'}, 
            $this->getAccountUri($this->user));

        $bankAccount->setBalancedUri($data->{'uri'});
        $this->em->flush();

        return true;
    }

    public function deleteBankAccount(BankAccount $bankAccount)
    {
        if ($this->debug) {
            $this->logger->info(
                printf("[Balanced Payment] Removing bank account %d", 
                $bankAccount->getId())
            );
        }

        $data = $this->balancedPayment->deleteBankAccount($bankAccount);
        $this->em->remove($bankAccount);
        $this->em->flush();

        return true;
    }

    public function createCard(Card $card)
    {
        if ($this->debug) {
            $this->logger->info(
                printf("[Balanced Payment] Adding card %d", 
                $card->getId())
            );
        }

        $cardData = $this->balancedPayment->createCard($card);
        $attachData = $this->balancedPayment->attachCard($cardData->{'uri'}, 
            $this->getAccountUri($this->user));

        $card->setBalancedUri($attachData->{'uri'});
        $this->em->flush();

        return true;
    }

    public function deleteCard(Card $card)
    {
        if ($this->debug) {
            $this->logger->info(
                printf("[Balanced Payment] Removing card %d", 
                $card->getId())
            );
        }

        $data = $this->balancedPayment->deleteCard($card);
        $this->em->remove($card);
        $this->em->flush();

        return true;
    }

    public function credit(BankAccount $bankAccount, $amount)
    {
        if ($this->debug) {
            $this->logger->info(
                printf("[Balanced Payment] Creating a credit of %d to %d", 
                $amount, $bankAccount->getId())
            );
        }

        $ba = $this->balancedPayment->credit($bankAccount->getBalancedUri(), $amount);

        return true;
    }

    public function debit(Card $card, $amount, $statement = null, $description = null)
    {
        if ($this->debug) {
            $this->logger->info(
                printf("[Balanced Payment] Creating a debit of %d from %d", 
                $amount, $card->getId())
            );
        }

        $ca = $this->balancedPayment->getCard($card->getBalancedUri());
        $accountUri = $this->getAccountUri($this->user);

        $this->balancedPayment->debit($accountUri, $ca->{'uri'}, $amount, $statement, $description);
        return true;
    }

    public function promoteToMerchant()
    {
        if ($this->debug) {
            $this->logger->info(
                printf("[Balanced Payment] Promoting user %s to a merchant", 
                $user->getId())
            );
        }

        $accountUri = $this->getAccountUri($this->user);
        $account = $this->balancedPayment->getAccount($accountUri);
        $this->balancedPayment->promoteToMerchant($account, $this->user);

        return true;
    }

    protected function getAccountUri(BalancedUser $user)
    {
        return $user->getBalancedUri();
    }
}
