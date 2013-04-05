<?php

namespace Jm\BalancedPaymentBundle\Model;

class BalancedPayment
{
    private $balancedFactory;

    public function __construct(BalancedFactory $balancedFactory)
    {
        $this->balancedFactory = $balancedFactory;
    }

    public function createAccount($email)
    {
        return $this->balancedFactory->getMarketPlace()->createAccount($email);
    }

    public function getAccount($uri)
    {
        return $this->balancedFactory->getAccount($uri);
    }

    public function createBankAccount(BankAccount $bankAccount)
    {
        $bank_account = $this->balancedFactory->createBankAccount(
            array(
            "routing_number" => $bankAccount->getRoutingNumber(),
            "type" => $bankAccount->getType(),
            "name" => $bankAccount->getOwner()->getUsername(),
            "account_number" => $bankAccount->getAccountNumber(),
        ));

        return $obj = $bank_account->save();
    }

    public function getBankAccount($uri)
    {
        return $this->balancedFactory->getBankAccount($uri);
    }

    public function listBankAccounts($accountUri)
    {
        //$account = $this->getAccount($accountUri);
    }

    public function attachBankAccount($bankAccountUri, $accountUri)
    {
        $account = $this->getAccount($accountUri);

        return $account->addBankAccount($bankAccountUri);
    }

    public function deleteBankAccount($bankAccountUri)
    {
        $bank_account = $this->getBankAccount($bankAccountUri);
        $bank_account->delete();
    }

    public function createCard(Card $card)
    {
        return $this->balancedFactory
            ->getMarketPlace()
            ->createCard(
                null, null, null, null, null,
                $card->getNumber(),
                $card->getCvv(),
                $card->getExpirationMonth(),
                $card->getExpirationYear()
            )
        ;
    }

    public function getCard($uri)
    {
        return $this->balancedFactory->getCard($uri);
    }

    public function attachCard($creditCardUri, $accountUri)
    {
        $account = $this->getAccount($accountUri);

        return $account->addCard($creditCardUri);
    }

    public function deleteCard($cardUri)
    {
        $card = $this->getCard($cardUri);
        $card->delete();
    }

    public function debit($accountUri, $cardUri, $amount, $appearsOnStatement = null, $description = null, $meta = null)
    {
        $creditCard = $this->getCard($cardUri);

        $account = $this->getAccount($accountUri);

        return $account->debit($amount, $appearsOnStatement, $description, $meta, $creditCard->{'uri'});
    }

    public function credit($bankAccountUri, $amount, $description = null, $meta = null, $appearsOnStatement = null)
    {
        $bankAccount = $this->getBankAccount($bankAccountUri);

        return $bankAccount->credit($amount, $description, $meta, $appearsOnStatement);
    }

    private function getDebit($uri)
    {
        return $this->balancedFactory->getDebit($uri);
    }

    private function getCredit($uri)
    {
        return $this->balancedFactory->getCredit($uri);
    }

    public function promoteToMerchant($accountUri, $data = array())
    {
        $account = $this->getAccount($accountUri);
        
        return $account->promoteToMerchant($data);
    }
}
