<?php

namespace Jm\BalancedPaymentBundle\Tests\Model;

class BalancedPaymentTest extends \PHPUnit_Framework_TestCase
{
    private $balancedPayment;
    private $email;
    private $factory;

    protected function setUp()
    {
        $this->factory = $this->getBalancedFactory();
        $this->balancedPayment = $this->getBalancedPayment($this->factory);

        $this->email = uniqid(true) . "@test123.com";
    }

    public function testCreateAccount()
    {
        $marketPlace = $this->getMarketplace();
        $this->factory->expects($this->once())
            ->method('getMarketPlace')
            ->will($this->returnValue($marketPlace))
            ;
        $marketPlace->expects($this->once())
            ->method('createAccount')
            ->with($this->email)
        ;

        $this->balancedPayment->createAccount($this->email);
    }

    public function testGetAccount()
    {
        $url = "http://uri";
        $this->factory->expects($this->once())
            ->method('getAccount')
            ->with($url)
            ;

        $this->balancedPayment->getAccount($url);
    }

    public function testCreateBankAccount()
    {
        $user = $this->getUser();
        $user->expects($this->once())
            ->method('getUsername')
            ->will($this->returnValue('username'))
        ;

        $bankAccount = $this->getBankAccount();
        $balancedBankAccount = $this->getBalancedBankAccount();
        $bankAccount->expects($this->once())
            ->method('getRoutingNumber')
            ->will($this->returnValue('routing'))
        ;
        $bankAccount->expects($this->once())
            ->method('getType')
            ->will($this->returnValue('checking'))
        ;
        $bankAccount->expects($this->once())
            ->method('getOwner')
            ->will($this->returnValue($user))
        ;
        $bankAccount->expects($this->once())
            ->method('getAccountNumber')
            ->will($this->returnValue('account'))
        ;
        $balancedBankAccount->expects($this->once())
            ->method('save')
        ;


        $this->factory->expects($this->once())
            ->method('createBankAccount')
            ->with(array(
                'routing_number' => 'routing',
                'type' => 'checking',
                'name' => 'username',
                'account_number' => 'account',
            ))
            ->will($this->returnValue($balancedBankAccount))
        ;

        $this->balancedPayment->createBankAccount($bankAccount); 
    }

    public function testGetBankAccount()
    {
        $uri = "http://uri";
        $this->factory->expects($this->once())
            ->method('getBankAccount')
            ->with($uri)
        ;

       $this->balancedPayment->getBankAccount($uri);
    }

    public function testAttachBankAccount()
    {
        $accountUri = "http://account.uri";
        $bankAccountUri = "http://bank.uri";

        $account = $this->getAccount();
        $this->factory->expects($this->once())
            ->method('getAccount')
            ->with($accountUri)
            ->will($this->returnValue($account))
        ;

        $this->balancedPayment->attachBankAccount($bankAccountUri, $accountUri);
    }

    public function testDeleteBankAccount()
    {
        $bankAccountUri = "http://bank.uri";
        $bankAccount = $this->getBalancedBankAccount();
        $this->factory->expects($this->once())
            ->method('getBankAccount')
            ->with($bankAccountUri)
            ->will($this->returnValue($bankAccount))
        ;
        $bankAccount->expects($this->once())
            ->method('delete')
        ;

        $this->balancedPayment->deleteBankAccount($bankAccountUri);
    }

    public function testCreateCard()
    {
        $card = $this->getCard();
        $balancedCard = $this->getBalancedCard();
        $marketPlace = $this->getMarketPlace();

        $card->expects($this->once())
            ->method('getNumber')
            ->will($this->returnValue('number'))
        ;
        $card->expects($this->once())
            ->method('getCvv')
            ->will($this->returnValue('cvv'))
        ;
        $card->expects($this->once())
            ->method('getExpirationMonth')
            ->will($this->returnValue('month'))
        ;
        $card->expects($this->once())
            ->method('getExpirationYear')
            ->will($this->returnValue('year'))
        ;
        $this->factory->expects($this->once())
            ->method('getMarketPlace')
            ->will($this->returnValue($marketPlace))
            ;
        $marketPlace->expects($this->once())
            ->method('createCard')
        ;

        $this->balancedPayment->createCard($card);
    }

    public function testGetCard()
    { 
        $cardUri = "http://card.uri";
        $this->factory->expects($this->once())
            ->method('getCard')
            ->with($cardUri)
            ;

        $this->balancedPayment->getCard($cardUri);
    }

    public function testAttachCard()
    {
        $accountUri = "http://uri";
        $cardUri = "http://card.uri";

        $account = $this->getAccount();
        $this->factory->expects($this->once())
            ->method('getAccount')
            ->with($accountUri)
            ->will($this->returnValue($account))
            ;
        $account->expects($this->once())
            ->method('addCard')
            ->with($cardUri)
        ;


        $this->balancedPayment->attachCard($cardUri, $accountUri);
    }

    public function testDeleteCard()
    {
        $cardUri = "http://card.uri";
        $card = $this->getBalancedCard();
        $this->factory->expects($this->once())
            ->method('getCard')
            ->with($cardUri)
            ->will($this->returnValue($card))
        ;
        $card->expects($this->once())
            ->method('delete')
        ;

        $this->balancedPayment->deleteCard($cardUri);
    }

    public function testDebit()
    {
        $cardUri = "http://card.uri";
        $card = $this->getBalancedCard();
        $account = $this->getAccount();
        $this->factory->expects($this->once())
            ->method('getCard')
            ->with($cardUri)
            ->will($this->returnValue($card))
            ;

        $accountUri = "http://uri";
        $this->factory->expects($this->once())
            ->method('getAccount')
            ->with($accountUri)
            ->will($this->returnValue($account))
            ;
        $account->expects($this->once())
            ->method('debit')
            ->with("1000", "statement")
            ;

        $this->balancedPayment->debit($accountUri, $cardUri, 1000, "statement");
    }

    public function testCredit()
    {
        $bankAccountUri = "http://bank.uri";
        $bankAccount = $this->getBalancedBankAccount();
        $this->factory->expects($this->once())
            ->method('getBankAccount')
            ->with($bankAccountUri)
            ->will($this->returnValue($bankAccount))
            ;
        $bankAccount->expects($this->once())
            ->method('credit')
            ->with(1000)
        ;

        $data = $this->balancedPayment->credit($bankAccountUri, 1000);
    }

    public function testPromoteToMerchant()
    {
    }

    private function getBalancedFactory()
    {
        return $this->getMockBuilder('Jm\BalancedPaymentBundle\Model\BalancedFactory')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    private function getAccount()
    {
        return $this->getMock('\Balanced\Account');
    }

    private function getMarketplace()
    {
        return $this->getMock('\Balanced\Marketplace');
    }

    private function getBalancedBankAccount()
    {
        return $this->getMock('\Balanced\BankAccount');
    }

    private function getBalancedCard()
    {
        return $this->getMock('\Balanced\Card');
    }

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

    private function getBalancedPayment($factory)
    {
        return new \Jm\BalancedPaymentBundle\Model\BalancedPayment($factory);
    }
}
