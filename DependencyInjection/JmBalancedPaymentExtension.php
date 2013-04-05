<?php

namespace Jm\BalancedPaymentBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class JmBalancedPaymentExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('jm_balanced_payment.api_key', $config['api_key']);
        $container->setParameter('jm_balancedpayment.marketplace_user_id', $config['marketplace_user_id']);
        $container->setParameter('jm_balancedpayment.user_class', $config['user_class']);
        $container->setParameter('jm_balancedpayment.doctrine_listener', $config['doctrine_listener']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        if ($config['doctrine_listener']) {
            $loader->load('doctrine.xml');
        }
    }
}
