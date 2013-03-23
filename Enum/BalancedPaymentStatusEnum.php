<?php

namespace Jm\BalancedPaymentBundle\Enum;

class BalancedPaymentStatusEnum extends Enum
{
    const PENDING = 'pending';

    //credit
    const PAID = 'paid';

    //debit
    const SUCCEEDED = 'succeeded';
}
