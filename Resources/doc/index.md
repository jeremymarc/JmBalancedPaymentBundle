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


### Step 3: Configure the JmBalancedPaymentBundle

``` yaml
# app/config/config.yml
jm_balancedpayment:
    api_key: 'BALANCED API KEY'
    user_class: Acme\UserBundle\Entity\User
    marketplace_user_id: '1'
    doctrine_listener: true
```

There are 3 parameters required to configured the bundle.

##### api_key
It's the Balanced Payment API key we need to interact with them.

##### user_class
CreditCard, BankAccount and BalancedPayment are attached to an user.
We need to know your application user entity class (for example Acme\CoreBundle\Entity\User)

##### marketplace_userid
This is your marketplace user ID. Payment are done between 2 users.
For example a debit is set from a user X (->setFromUser(X)) to this marketplace 
user id (->setToUser(marketplace_user_id)) and inverse for debit.

##### doctrine_listener (Optional, default false)
Activate the Doctrine listener which keep CreditCard and BankAccount 
transparently in sync with BalancedPayment: 
https://github.com/jeremymarc/JmBalancedPaymentBundle/blob/master/Doctrine/Listener/PaymentSourceListener.php
 


### Step 4: Updating schema
Your User class must implements Jm\BalancedPaymentBundle\Entity\BalancedUserInterface :
https://github.com/jeremymarc/JmBalancedPaymentBundle/blob/master/Entity/BalancedUserInterface.php

To update your application schema, just run the command : 
```
./app/console doctrine:schema:update --force

That's it. You can now use the bundle. 

[PaymentManager API](https://github.com/jeremymarc/JmBalancedPaymentBundle/blob/master/Resources/doc/manager.md)
