<?php

namespace Jm\BalancedPaymentBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SyncBalancedPaymentStatusCommand extends Command
{
    protected $em;
    protected $logger;

    protected function configure()
    {
        $this->setName('jm:balancedpayment:payment:sync')
            ->setDescription('Sync status between Payment and Balanced')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        set_time_limit(0);
        $time_start = microtime(true);
        
        $container = $this->getApplication()->getKernel()->getContainer();
        $this->em = $container->get('doctrine')->getEntityManager();
        $this->logger = $container->get('logger');

        $paymentManager = $container->get('jm_balancedpayment.payment.manager');
        $payments = $this->getPaymentRepository()->findPending();

        foreach($payments as $payment) {
            $message = sprintf("Syncing payment %d", $payment->getId());
            $output->writeln($message);
            $logger->info($message);

            $localData = json_decode($payment->getData());
            $paymentUri = $localData->{'uri'};

            if ($payment->isCredit()) {
                $gatewayData = $paymentManager->getCredit($paymentUri);
            } else {
                $gatewayData = $paymentManager->getDebit($paymentUri);
            }

            if ($localData->{'status'} != $gatewayData->{'status'}) {
                $message = sprintf("State for payment %d has changed from %s to %s", 
                    $payment->getId(),
                    $localData->{'state'},
                    $gatewayData->{'state'}
                );
                $output->writeln($message);
                $logger->info($message);
                    
                $payment
                    ->setState($gatewayData->{'state'})
                    ->setData(json_encode($gatewayData))
                ;

                $em->persist($payment);
            }
        }

        $this->em->flush();

        $time_end = microtime(true);
        $output->writeln('[' . date('Y-m-d H:i:s') . '] [jm_balancedpayment:payment:sync] COMMAND OK (' .  round($time_end - $time_start, 2) . "ms) \n");
    }

    private function getPaymentRepository()
    {
        return $this->em->getRepository('JmBalancedPaymentBundle:BalancedPayment');
    }
}
