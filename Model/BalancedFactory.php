<?php

namespace Jm\BalancedPaymentBundle\Model;

class BalancedFactory
{
    public function __construct($apiKey)
    {
        \Httpful\Bootstrap::init();
        \RESTful\Bootstrap::init();
        \Balanced\Bootstrap::init();

        \Balanced\Settings::$api_key = $apiKey;
    }

    public function getMarketPlace()
    {
        return \Balanced\Marketplace::mine();
    }

    public function getAccount($uri)
    {
        return \Balanced\Account::get($uri);
    }

    public function createAccount($options)
    {
        return new \Balanced\Account($options);
    }

    public function createBankAccount($options)
    {
        return new \Balanced\BankAccount($options);
    }

    public function getBankAccount($uri)
    {
        return \Balanced\BankAccount::get($uri);
    }

    public function getCard($uri)
    {
        return \Balanced\Card::get($uri);
    }

    public function getDebit($uri)
    {
        return \Balanced\Debit::get($uri);
    }

    public function getCredit($uri)
    {
        return \Balanced\Credit::get($uri);
    }
}
