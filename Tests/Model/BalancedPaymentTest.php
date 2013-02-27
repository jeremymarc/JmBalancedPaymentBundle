<?php

namespace Jm\BalancedPaymentBundle\Tests\Model;

class BalancedPaymentTest extends \PHPUnit_Framework_TestCase
{
    private $balancedPayment;
    private $email;

    protected function setUp()
    {
        $testApiKey = "5ea5f75e807d11e2bbd9026ba7d31e6f";
        $this->balancedPayment = $this->getBalancedPayment($testApiKey);
        $this->email = uniqid(true) . "@test123.com";
    }

    public function testCreateAccount()
    {
        $data = $this->balancedPayment->createAccount($this->email);

        $this->assertInstanceOf('Balanced\Account', $data);
        $this->assertObjectHasAttribute('uri', $data);
        $this->assertObjectHasAttribute('email_address', $data);
        $this->assertObjectHasAttribute('created_at', $data);
    }

    public function testGetAccount()
    {
        $data = $this->balancedPayment->createAccount($this->email);
        $accountUri = $data->{'uri'};
        $data = $this->balancedPayment->getAccount($accountUri);

        $this->assertInstanceOf('Balanced\Account', $data);
        $this->assertObjectHasAttribute('uri', $data);
        $this->assertObjectHasAttribute('email_address', $data);
        $this->assertObjectHasAttribute('created_at', $data);
       
        $this->assertEquals($data->{'uri'}, $accountUri);
    }

    public function testCreateBankAccount()
    {
        $bankAccount = $this->getBankAccount();
        $user = $this->getUser();
        $user->expects($this->once())
            ->method('getUsername')
            ->will($this->returnValue('Username'))
        ;

        $bankAccount->expects($this->once())
            ->method('getRoutingNumber')
            ->will($this->returnValue('021000021'))
        ; 
        $bankAccount->expects($this->once())
            ->method('getAccountNumber')
            ->will($this->returnValue('9900000002'))
        ; 
        $bankAccount->expects($this->once())
            ->method('getType')
            ->will($this->returnValue('savings'))
        ; 
        $bankAccount->expects($this->once())
            ->method('getOwner')
            ->will($this->returnValue($user))
        ; 

        $data = $this->balancedPayment->createBankAccount($bankAccount); 
        $this->assertInstanceOf('Balanced\BankAccount', $data);
        $this->assertObjectHasAttribute('account_number', $data);
        $this->assertObjectHasAttribute('bank_name', $data);
        $this->assertObjectHasAttribute('can_debit', $data);
        $this->assertObjectHasAttribute('uri', $data);
        $this->assertObjectHasAttribute('routing_number', $data);
        $this->assertObjectHasAttribute('type', $data);
        
        $this->assertEquals($data->{'routing_number'}, '021000021');
        $this->assertEquals($data->{'account_number'}, 'xxxxxx0002');
        $this->assertEquals($data->{'type'}, 'savings');
    }

    public function testGetBankAccount()
    {
        $bankAccount = $this->getBankAccount();
        $user = $this->getUser();
        $user->expects($this->once())
            ->method('getUsername')
            ->will($this->returnValue('Username'))
        ;

        $bankAccount->expects($this->once())
            ->method('getRoutingNumber')
            ->will($this->returnValue('021000021'))
        ; 
        $bankAccount->expects($this->once())
            ->method('getAccountNumber')
            ->will($this->returnValue('9900000002'))
        ; 
        $bankAccount->expects($this->once())
            ->method('getType')
            ->will($this->returnValue('savings'))
        ; 
        $bankAccount->expects($this->once())
            ->method('getOwner')
            ->will($this->returnValue($user))
        ; 

        $data = $this->balancedPayment->createBankAccount($bankAccount); 
        $bankAccount = $this->balancedPayment->getBankAccount($data->{'uri'});

        $this->assertInstanceOf('Balanced\BankAccount', $bankAccount);
        $this->assertEquals($bankAccount->{'routing_number'}, '021000021');
        $this->assertEquals($bankAccount->{'account_number'}, 'xxxxxx0002');
        $this->assertEquals($bankAccount->{'type'}, 'savings');
    }

    public function testAttachBankAccount()
    {
        $bankAccount = $this->getBankAccount();
        $user = $this->getUser();
        $user->expects($this->once())
            ->method('getUsername')
            ->will($this->returnValue('Username'))
        ;

        $bankAccount->expects($this->once())
            ->method('getRoutingNumber')
            ->will($this->returnValue('021000021'))
        ; 
        $bankAccount->expects($this->once())
            ->method('getAccountNumber')
            ->will($this->returnValue('9900000002'))
        ; 
        $bankAccount->expects($this->once())
            ->method('getType')
            ->will($this->returnValue('savings'))
        ; 
        $bankAccount->expects($this->once())
            ->method('getOwner')
            ->will($this->returnValue($user))
        ; 

        $bankAccountData = $this->balancedPayment->createBankAccount($bankAccount); 
        $userData = $this->balancedPayment->createAccount($this->email);
        $data = $this->balancedPayment->attachBankAccount($bankAccountData->{'uri'}, $userData->{'uri'});

        $this->assertInstanceOf('Balanced\Account', $data);
        $this->assertEquals($userData->{'email_address'}, $data->{'email_address'});

        //todo list all bankaccount; look's like it's missing inside balanced-php
    }

    /**
     * @expectedException RESTful\Exceptions\HTTPError
     */
    public function testDeleteBankAccount()
    {
        $bankAccount = $this->getBankAccount();
        $user = $this->getUser();
        $user->expects($this->once())
            ->method('getUsername')
            ->will($this->returnValue('Username'))
        ;

        $bankAccount->expects($this->once())
            ->method('getRoutingNumber')
            ->will($this->returnValue('021000021'))
        ; 
        $bankAccount->expects($this->once())
            ->method('getAccountNumber')
            ->will($this->returnValue('9900000002'))
        ; 
        $bankAccount->expects($this->once())
            ->method('getType')
            ->will($this->returnValue('savings'))
        ; 
        $bankAccount->expects($this->once())
            ->method('getOwner')
            ->will($this->returnValue($user))
        ; 

        $bankAccountData = $this->balancedPayment->createBankAccount($bankAccount); 
        $this->assertInstanceOf('Balanced\BankAccount', $bankAccountData);

        $bankAccountData2 = $this->balancedPayment->getBankAccount($bankAccountData->{'uri'});
        $this->assertEquals($bankAccountData->{'uri'}, $bankAccountData2->{'uri'});

        $this->balancedPayment->deleteBankAccount($bankAccountData->{'uri'});
        $bankAccountData2 = $this->balancedPayment->getBankAccount($bankAccountData->{'uri'});
    }

    public function testCreateCard()
    {
        $creditCard = $this->getCard();
        $creditCard->expects($this->once())
            ->method('getNumber')
            ->will($this->returnValue('4111111111111111'))
        ;
        $creditCard->expects($this->once())
            ->method('getCvv')
            ->will($this->returnValue('123'))
        ;
        $creditCard->expects($this->once())
            ->method('getExpirationMonth')
            ->will($this->returnValue('01'))
            ;
        $creditCard->expects($this->once())
            ->method('getExpirationYear')
            ->will($this->returnValue('2016'))
            ;

        $data = $this->balancedPayment->createCard($creditCard);

        $this->assertInstanceOf('Balanced\Card', $data);
        $this->assertObjectHasAttribute('account', $data);
        $this->assertObjectHasAttribute('brand', $data);
        $this->assertObjectHasAttribute('can_debit', $data);
        $this->assertObjectHasAttribute('card_type', $data);
        $this->assertObjectHasAttribute('expiration_month', $data);
        $this->assertObjectHasAttribute('expiration_year', $data);
        $this->assertObjectHasAttribute('hash', $data);
        $this->assertObjectHasAttribute('uri', $data);
        $this->assertObjectHasAttribute('id', $data);
        $this->assertObjectHasAttribute('is_valid', $data);
        
        //$this->assertEquals($data->{'expiration_month'}, '01');
        //$this->assertEquals($data->{'account_year'}, '2016');
    }

    public function testGetCard()
    {
        $creditCard = $this->getCard();
        $creditCard->expects($this->once())
            ->method('getNumber')
            ->will($this->returnValue('4111111111111111'))
        ;
        $creditCard->expects($this->once())
            ->method('getCvv')
            ->will($this->returnValue('123'))
        ;
        $creditCard->expects($this->once())
            ->method('getExpirationMonth')
            ->will($this->returnValue('01'))
            ;
        $creditCard->expects($this->once())
            ->method('getExpirationYear')
            ->will($this->returnValue('2016'))
            ;

        $creditCardData = $this->balancedPayment->createCard($creditCard);
        $this->assertInstanceOf('Balanced\Card', $creditCardData);

        $creditCardData2 = $this->balancedPayment->getCard($creditCardData->{'uri'});
        $this->assertInstanceOf('Balanced\Card', $creditCardData);
        $this->assertEquals($creditCardData->{'uri'}, $creditCardData2->{'uri'});
    }

    public function testAttachCard()
    {
        $creditCard = $this->getCard();
        $creditCard->expects($this->once())
            ->method('getNumber')
            ->will($this->returnValue('4111111111111111'))
        ;
        $creditCard->expects($this->once())
            ->method('getCvv')
            ->will($this->returnValue('123'))
        ;
        $creditCard->expects($this->once())
            ->method('getExpirationMonth')
            ->will($this->returnValue('01'))
            ;
        $creditCard->expects($this->once())
            ->method('getExpirationYear')
            ->will($this->returnValue('2016'))
            ;

        $creditCardData = $this->balancedPayment->createCard($creditCard);
        $this->assertInstanceOf('Balanced\Card', $creditCardData);

        $userData = $this->balancedPayment->createAccount($this->email);
        $this->assertInstanceOf('Balanced\Account', $userData);

        $data = $this->balancedPayment->attachCard($creditCardData->{'uri'}, $userData->{'uri'});
        //todo: add list test
    }

    /**
     * @expectedException RESTful\Exceptions\HTTPError
     */
    public function testDeleteCard()
    {
        $creditCard = $this->getCard();
        $creditCard->expects($this->once())
            ->method('getNumber')
            ->will($this->returnValue('4111111111111111'))
        ;
        $creditCard->expects($this->once())
            ->method('getCvv')
            ->will($this->returnValue('123'))
        ;
        $creditCard->expects($this->once())
            ->method('getExpirationMonth')
            ->will($this->returnValue('01'))
            ;
        $creditCard->expects($this->once())
            ->method('getExpirationYear')
            ->will($this->returnValue('2016'))
            ;

        $creditCardData = $this->balancedPayment->createCard($creditCard);
        $this->assertInstanceOf('Balanced\Card', $creditCardData);
        $creditCardData->delete();

        $creditCardData2 = $this->balancedPayment->getCard($creditCardData->{'uri'});
        $this->assertInstanceOf('Balanced\Card', $creditCardData);
    }

    public function testDebit()
    {
        $accountData = $this->balancedPayment->createAccount($this->email);
        $this->assertInstanceOf('Balanced\Account', $accountData);
        $creditCard = $this->getCard();
        $creditCard->expects($this->once())
            ->method('getNumber')
            ->will($this->returnValue('4111111111111111'))
        ;
        $creditCard->expects($this->once())
            ->method('getCvv')
            ->will($this->returnValue('123'))
        ;
        $creditCard->expects($this->once())
            ->method('getExpirationMonth')
            ->will($this->returnValue('01'))
            ;
        $creditCard->expects($this->once())
            ->method('getExpirationYear')
            ->will($this->returnValue('2016'))
            ;

        $cardData = $this->balancedPayment->createCard($creditCard);
        $this->assertInstanceOf('Balanced\Card', $cardData);
        $this->balancedPayment->attachCard($cardData->{'uri'}, $accountData->{'uri'});

        $data = $this->balancedPayment->debit($accountData->{'uri'}, $cardData->{'uri'}, 1000, "statement");

        $this->assertInstanceOf('Balanced\Debit', $data);
        $this->assertObjectHasAttribute('amount', $data);
        $this->assertObjectHasAttribute('appears_on_statement_as', $data);
        $this->assertObjectHasAttribute('available_at', $data);
        $this->assertObjectHasAttribute('created_at', $data);
        $this->assertObjectHasAttribute('description', $data);
        $this->assertObjectHasAttribute('fee', $data);
        $this->assertObjectHasAttribute('status', $data);
        $this->assertObjectHasAttribute('transaction_number', $data);
        $this->assertObjectHasAttribute('uri', $data);

        $this->assertEquals($data->{'amount'}, 1000);
        $this->assertEquals($data->{'appears_on_statement_as'}, 'statement');
        $this->assertEquals($data->{'status'}, 'succeeded');
    }

    public function testPromoteToMerchant()
    {}

    public function testGetCredit()
    {}

    private function getUser()
    {
        return $this->getMock('FOS\UserBundle\Model\User');
    }

    private function getBankAccount()
    {
        return $this->getMock('Jm\BalancedPaymentBundle\Model\BankAccount');
    }

    public function getCard()
    {
        return $this->getMock('Jm\BalancedPaymentBundle\Model\Card');
    }

    private function getBalancedPayment($apiKey)
    {
        return new \Jm\BalancedPaymentBundle\Model\BalancedPayment($apiKey);
    }
}
