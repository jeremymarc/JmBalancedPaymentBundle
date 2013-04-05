<?php

namespace Jm\BalancedPaymentBundle\Tests\Model;

use Jm\BalancedPaymentBundle\Model\BalancedPayment;
use Jm\BalancedPaymentBundle\Model\PaymentManager;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\ORM\EntityManager;

class PaymentManagerTest extends \PHPUnit_Framework_TestCase
{
    private $balancedPayment;
    private $dispatcher;
    private $user;
    private $em;
    private $logger;
    private $manager;
    private $email;

    protected function setUp()
    {
        $this->balancedPayment = $this->getBalancedPayment();
        $this->dispatcher = $this->getDispatcher();

        $this->user = $this->getUser();
        $this->em = $this->getEm();
        $this->logger = $this->getLogger();
        $this->manager = $this->getPaymentManager($this->balancedPayment,
            $this->em, $this->logger, $this->dispatcher,
            'Jm\BalancedPaymentBundle\Entity\BalancedUserInterface', 1, false);

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
        $this->em->expects($this->once())
            ->method('flush')
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
        $user = $this->getUser();
        $user->expects($this->once())
            ->method('getBalancedUri')
            ->will($this->returnValue('http://user.uri'))
        ;
        $this->balancedPayment->expects($this->once())
            ->method('attachBankAccount')
            ->with('http://uri', 'http://user.uri')
            ->will($this->returnValue($returnObject))
        ;

        $result = $this->manager->createBankAccount($bankAccount, $user);
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
        $user = $this->getUser();
        $user->expects($this->once())
            ->method('getBalancedUri')
            ->will($this->returnValue('http://user.uri'))
        ;
        $this->balancedPayment->expects($this->once())
            ->method('attachCard')
            ->with('http://uri', 'http://user.uri')
            ->will($this->returnValue($returnObject))
        ;

        $result = $this->manager->createCard($card, $user);
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

        $result = $this->manager->credit($bankAccount, $this->user, 1000, 'reference');
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

        $user = $this->getUser();
        $user->expects($this->once())
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
            ->with("http://account.uri", "http://card.uri", "1000", "statement",
                "description", "meta")
        ;

        $result = $this->manager->debit($card, $user, 1000, "reference",
            "statement", "description", "meta");

        $this->assertTrue($result);
    }

    public function testPromoteToMerchant()
    {
        $accountUri = "http://account.uri";
        $data = array();

        $this->user
            ->expects($this->once())
            ->method('getBalancedUri')
            ->will($this->returnValue($accountUri))
        ;

        $this->balancedPayment
            ->expects($this->once())
            ->method('promoteToMerchant')
            ->with($accountUri, $data)
        ;

        $this->manager->promoteToMerchant($this->user, $data);
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

    private function getDispatcher()
    {
        return $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
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
        return $this->getMockBuilder('Symfony\Component\HttpKernel\Log\LoggerInterface')
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
        EntityManager $em, LoggerInterface $logger, EventDispatcherInterface $dispatcher,
        $userClass, $marketplaceUserId, $debug)
    {
        return new PaymentManager($bp, $em, $logger, $dispatcher,
            $userClass, $marketplaceUserId, $debug);
    }
}
