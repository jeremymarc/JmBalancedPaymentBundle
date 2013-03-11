<?php
 
namespace Jm\BalancedPaymentBundle\Enum;
 
class PaymentStatusEnum extends Enum
{
    const PENDING = 'pending';

    //credit
    const PAID = 'paid';

    //debit
    const SUCCEEDED = 'succeeded';
}
