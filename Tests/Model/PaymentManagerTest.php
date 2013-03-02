<?php

namespace Jm\BalancedPaymentBundle\Tests\Model;

use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityManager;
use Jm\BalancedPaymentBundle\Model\BalancedPayment;
use Jm\BalancedPaymentBundle\Model\PaymentManager;
use Symfony\Bridge\Monolog\Logger;

class PaymentManagerTest extends \PHPUnit_Framework_TestCase
{
    private $balancedPayment;
    private $context;
    private $user;
    private $em;
    private $logger;
    private $manager;
    private $email;

    protected function setUp()
    {
        $this->balancedPayment = $this->getBalancedPayment();
        $this->context = $this->getSecurityContext();

        $token = $this->getAuthenticationToken();
        $this->user = $this->getUser();
        $token->expects($this->once())
            ->method('getUser')
            ->will($this->returnValue($this->user))
        ;
        $this->context->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue($token))
        ;

        $this->em = $this->getEm();

        $this->logger = $this->getLogger();
        $this->manager = $this->getPaymentManager($this->balancedPayment,
            $this->context, $this->em, $this->logger, false);

        $this->email = uniqid(true) . "@test123.com";
    }

    public function testCreateAccount()
    {
        $returnObject = new \stdClass;
        $returnObject->{'uri'} = "http://uri";
        $this->balancedPayment->expects($this->once())
            ->method('createAccount')
            ->will($this->returnValue($returnObject))
        ;

        $user = $this->getUser();
        $user->expects($this->once())
            ->method('getEmail')
            ->will($this->returnValue($this->email))
        ;
        $user->expects($this->once())
            ->method('setBalancedUri')
            ->with('http://uri')
        ;

        $result = $this->manager->createAccount($user);
        $this->assertTrue($result);
    }

    public function testAddBankAccount()
    {
        $returnObject = new \stdClass;
        $returnObject->{'uri'} = "http://uri";

        $bankAccount = $this->getBankAccount();
        $bankAccount->expects($this->once())
            ->method('setBalancedUri')
            ->with('http://uri')
        ;
        $this->balancedPayment->expects($this->once())
            ->method('createBankAccount')
            ->with($bankAccount)
            ->will($this->returnValue($returnObject))
        ;
        $this->balancedPayment->expects($this->once())
            ->method('attachBankAccount')
            ->will($this->returnValue($returnObject))
        ;

        $result = $this->manager->createBankAccount($bankAccount);
        $this->assertTrue($result);
    }

    public function testDeleteBankAccount()
    {
        $bankAccount = $this->getBankAccount();
        $this->balancedPayment->expects($this->once())
            ->method('deleteBankAccount')
            ->with($bankAccount)
        ;
        $this->em->expects($this->once())
            ->method('remove')
            ->with($bankAccount)
        ;
        $this->em->expects($this->once())
            ->method('flush')
        ;

        $result = $this->manager->deleteBankAccount($bankAccount);
        $this->assertTrue($result);
    }

    public function testAddCard()
    {
        $returnObject = new \stdClass;
        $returnObject->{'uri'} = "http://uri";

        $card = $this->getCard();
        $card->expects($this->once())
            ->method('setBalancedUri')
            ->with('http://uri')
        ;
        $this->balancedPayment->expects($this->once())
            ->method('createCard')
            ->with($card)
            ->will($this->returnValue($returnObject))
        ;
        $this->balancedPayment->expects($this->once())
            ->method('attachCard')
            ->will($this->returnValue($returnObject))
        ;

        $result = $this->manager->createCard($card);
        $this->assertTrue($result);
    }

    public function testDeleteCard()
    {
        $card = $this->getCard();
        $this->balancedPayment->expects($this->once())
            ->method('deleteCard')
            ->with($card)
        ;
        $this->em->expects($this->once())
            ->method('remove')
            ->with($card)
        ;
        $this->em->expects($this->once())
            ->method('flush')
        ;

        $result = $this->manager->deleteCard($card);
        $this->assertTrue($result);
    }

    public function testCredit()
    {
        $bankAccount = $this->getBankAccount();
        $bankAccount->expects($this->once())
            ->method('getBalancedUri')
            ->will($this->returnValue("http://uri"))
        ;
        $this->balancedPayment->expects($this->once())
            ->method('credit')
            ->with('http://uri', 1000)
        ;

        $result = $this->manager->credit($bankAccount, 1000);
        $this->assertTrue($result);
    }

    public function testDebit()
    {
        $returnObject = new \stdClass;
        $returnObject->{'uri'} = "http://card.uri";

        $card = $this->getCard();
        $card->expects($this->once())
            ->method('getBalancedUri')
            ->will($this->returnValue("http://card.uri"))
        ;

        $this->user
            ->expects($this->once())
            ->method('getBalancedUri')
            ->will($this->returnValue("http://account.uri"))
        ;

        $this->balancedPayment
            ->expects($this->once())
            ->method('getCard')
            ->with("http://card.uri")
            ->will($this->returnValue($returnObject))
        ;
        $this->balancedPayment
            ->expects($this->once())
            ->method('debit')
            ->with("http://account.uri", "http://card.uri", "1000", "PAYMENT", "DESC")
        ;

        $result = $this->manager->debit($card, 1000, "PAYMENT", "DESC");
        $this->assertTrue($result);
    }

    public function testPromoteToMerchant()
    {
        $this->user
            ->expects($this->once())
            ->method('getBalancedUri')
            ->will($this->returnValue("http://account.uri"))
        ;

        $returnAccountObject = new \stdClass;
        $this->balancedPayment
            ->expects($this->once())
            ->method('getAccount')
            ->with("http://account.uri")
            ->will($this->returnValue($returnAccountObject))
        ;
        $this->balancedPayment
            ->expects($this->once())
            ->method('promoteToMerchant')
            ->with($returnAccountObject, $this->user)
        ;

        $this->assertTrue($this->manager->promoteToMerchant());
    }


    private function getCard()
    {
        return $this->getMockBuilder('Jm\BalancedPaymentBundle\Model\Card')
            ->getMock()
        ;
    }

    private function getBankAccount()
    {
        return $this->getMockBuilder('Jm\BalancedPaymentBundle\Model\BankAccount')
            ->getMock()
        ;
    }

    private function getBalancedPayment()
    {
        return $this->getMockBuilder('Jm\BalancedPaymentBundle\Model\BalancedPayment')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    private function getAuthenticationToken()
    {
        return $this->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\TokenInterface')
            ->getMock()
        ;
    }

    private function getSecurityContext()
    {
        return $this->getMockBuilder('Symfony\Component\Security\Core\SecurityContext')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    private function getEm()
    {
        return $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    private function getLogger()
    {
        return $this->getMockBuilder('Symfony\Bridge\Monolog\Logger')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    private function getUser()
    {
        return $this->getMockBuilder('Jm\BalancedPaymentBundle\Model\BalancedUser')
            ->getMock()
        ;
    }

    private function getPaymentManager(BalancedPayment $bp, 
        SecurityContext $context, EntityManager $em, Logger $logger, $debug)
    {
        return new PaymentManager($bp, $context, $em, $logger, $debug);
    }
}
