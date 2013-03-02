Getting Started With JmBalancedPaymentBundle
============================================

## Installation

### Step 1: Download JmBalancedPaymentBundle using composer
```
$ composer require jm/balancedpayment-bundle
```

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Jm\BalancedPaymentBundle\JmBalancedPaymentBundle(),
    );
}
```


### Step 3: Create your Entities
We need to store cards and bankAccount to a database to save the element unique
id (uri for balancedPayment) and keep informations to display it to your users.
For security reasons, we are not storing all completed informations (some number 
of the credit card  and bank account are replaced by *).

The bundle provides base classes which are already mapped for most fields
to make it easier to create your entity. Here is how you use it:

1. Extend the base `Card` and `BankAccount` classes
2. Map the `id` field. It must be protected as it is inherited from the parent
class.

**Warning:**

> When you extend from the mapped superclass provided by the bundle, don't
> redefine the mapping for the other fields as it is provided by the bundle.

Your `Card` and `BankAccount` class can live inside any bundle in your application. 
For example, if you work at "Acme" company, then you might create a bundle called
`AcmePaymentBundle` and place your entity classes in it.

``` php
<?php
// src/Acme/PaymentBundle/Entity/Card.php

namespace Acme\PaymentBundle\Entity;

use Jm\BalancedPaymentBundle\Model\BankCard as BaseCard;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="card")
 */
class Card extends BaseCard
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
}
```

``` php
<?php
// src/Acme/PaymentBundle/Entity/BankAccount.php

namespace Acme\PaymentBundle\Entity;

use Jm\BalancedPaymentBundle\Model\BankAccount as BaseBankAccount;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="bank_account")
 */
class BankAccount extends BaseBankAccount
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
}
```

### Step 5: Configure the JmBalancedPaymentBundle
``` yaml
# app/config/config.yml
jm_balancedpayment:
    api_key: 'BALANCED API KEY'
```
