# PaymentManager API

### Create an account
```php
$manager = $this->get('jm_balancedpayment.payment.manager')
$manager->createAccount($user);
```


### Create a Bank Account
this method will create a BankAccount on BalancedPayment side, and attached it to
the current user.

```php
$manager = $this->get('jm_balancedpayment.payment.manager')
$manager->createBankAccount($user);
```

### Delete a Bank Account
this method delete a Bank Account in your application and on BalancedPayment side.

```php
$manager = $this->get('jm_balancedpayment.payment.manager')
$manager->deleteBankAccount($bankAccount);
```


### Create a Card
this method will create a Card on BalancedPayment side, and attached it to
the current user.

```php
$manager = $this->get('jm_balancedpayment.payment.manager')
$manager->createCard($card);
```


### Delete a Card
this method delete a Card in your application and on BalancedPayment side.

```php
$manager = $this->get('jm_balancedpayment.payment.manager')
$manager->deleteCard($bankAccount);
```


### Credit
Credit a Bank Account.

```php
$manager = $this->get('jm_balancedpayment.payment.manager')
$manager->deleteCard($bankAccount, $amount);
```


### Debit
Debit a Card.

```php
$manager = $this->get('jm_balancedpayment.payment.manager')
$manager->debit($card, $amount, $statement, $description);
```
$statement and $description are optionals.

TODO
### Promote to merchant
this method Promote the current user to a merchant.

```php
$manager = $this->get('jm_balancedpayment.payment.manager')
$manager->promoteToMerchant();
```
