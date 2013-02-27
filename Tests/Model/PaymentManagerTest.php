<?php

namespace Jm\BalancedPaymentBundle\Tests\Model;

class PaymentManagerTest extends \PHPUnit_Framework_TestCase
{
    private $manager;
    private $logger;

    protected function setUp()
    {
        $this->logger = $this->getLogger();
        $this->manager = $this->getPaymentManager(array(
            $this->logger
        ));
    }

    public function testCreateAccount()
    {
        $user = $this->getUser();
        $data = $this->manager->createAccount($user);
    }

    private function getLogger()
    {
        return $this->getMockBuilder('Symfony\Bridge\Monolog\Logger')
            ->disableOriginalConstructor()
        ;
    }

    private function getUser()
    {
        return $this->getMockBuilder('FOS\UserBundle\Model\User')
            //->getMockForAbstractClass()
            ->getMock()
        ;
    }

    private function getPaymentManager(array $args)
    {
        return $this->getMockBuilder('Jm\BalancedPaymentBundle\Model\PaymentManager')
            ->setConstructorArgs($args)
            ->getMock()
        ;
    }
}
