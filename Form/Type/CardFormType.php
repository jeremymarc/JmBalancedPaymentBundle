<?php

namespace Jm\BalancedPaymentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Jm\BalancedPaymentBundle\Enum\BankAccountTypeEnum;

class CardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', null, array(
                'label' => 'Credit card number'
            ))
            ->add('expirationMonth', null, array(
                'label' => 'Expiration month'
            ))
            ->add('expirationYear', null, array(
                'label' => 'Expiration year'
            ))
            ->add('cvv', null, array(
                'label' => 'CVV'
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jm\BalancedPaymentBundle\Entity\Card'
        ));
    }

    public function getName()
    {
        return 'creditcard';
    }
}
