<?php

namespace Jm\BalancedPaymentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Jm\BalancedPaymentBundle\Enum\BankAccountTypeEnum;
use JMS\DiExtraBundle\Annotation as DI;

class BankAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('routingNumber', null, array(
                'label' => 'Routing number'
            ))
            ->add('accountNumber', null, array(
                'label' => 'Account number'
            ))
            ->add('type', 'choice', array(
                'choices' => BankAccountTypeEnum::toArray()
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jm\BalancedPaymentBundle\Entity\BankAccount'
        ));
    }

    public function getName()
    {
        return 'bankaccount';
    }
}
