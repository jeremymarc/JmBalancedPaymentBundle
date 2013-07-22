# PaymentManager API

### Create an account
```php
$manager = $this->get('jm_balancedpayment.payment.manager')
$manager->createAccount(Jm\BalancedPaymentBundle\Entity\BalancedUserInterface $user);
```


### Create a Bank Account
This method will create a BankAccount on BalancedPayment side, and attach it to
the current user.

```php
$manager = $this->get('jm_balancedpayment.payment.manager')
$manager->createBankAccount(Jm\BalancedPaymentBundle\Model\BankAccount $bankAccount, Jm\BalancedPaymentBundle\Entity\BalancedUserInterface $user);
```

### Delete a Bank Account
This method delete a Bank Account in your application and on BalancedPayment side.

```php
$manager = $this->get('jm_balancedpayment.payment.manager')
$manager->deleteBankAccount(Jm\BalancedPaymentBundle\Model\BankAccount $bankAccount);
```


### Create a Card
This method will create a Card on BalancedPayment side, and attach it to
the current user.

```php
$manager = $this->get('jm_balancedpayment.payment.manager')
$manager->createCard(Jm\BalancedPaymentBundle\Model\Card $card, Jm\BalancedPaymentBundle\Entity\BalancedUserInterface $user);
```


### Delete a Card
This method will delete a Card in your application and on BalancedPayment side.

```php
$manager = $this->get('jm_balancedpayment.payment.manager')
$manager->deleteCard(Jm\BalancedPaymentBundle\Model\Card $card);
```


### Credit
Credit a Bank Account.

```php
$manager = $this->get('jm_balancedpayment.payment.manager')
$manager->deleteCard(Jm\BalancedPaymentBundle\Model\BankAccount $bankAccount, Jm\BalancedPaymentBundle\Entity\BalancedUserInterface $user, $amount, $reference, $description = null, $meta = null, $appearsOnStatement = null);
```


### Debit
Debit a Card.

```php
$manager = $this->get('jm_balancedpayment.payment.manager')
$manager->debit(Jm\BalancedPaymentBundle\Model\Card $card, Jm\BalancedPaymentBundle\Entity\BalancedUserInterface $user, $amount, $reference, $statement = null, $description = null, $meta = null);
```


### Promote to merchant
This method will promote the user to a merchant.

```php
$manager = $this->get('jm_balancedpayment.payment.manager')
$manager->promoteToMerchant(Jm\BalancedPaymentBundle\Entity\BalancedUserInterface $user, $data = array());
```
