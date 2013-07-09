JmBalancedPaymentBundle
=======================

The JmBalancedPaymentBundle integrate Balanced Payment library (https://github.com/balanced/balanced-php) 
to accept credit cards and debit bank accounts for your business. (more informations: https://www.balancedpayments.com/)

Features include:
- BankAccount / Card Entities using Doctrine 
- Doctrine EventListener to sync automatically
- Payment entity to list all debits/credits
- Unit tested

TODO: 
- Command to sync payments status
- Add Edit/Delete sync methods in the doctrinelistener for BankAccount/Card


Documentation
-------------

The bulk of the documentation is stored in the `Resources/doc/index.md`
file in this bundle:

[Read the Documentation for master](https://github.com/jeremymarc/JmBalancedPaymentBundle/blob/master/Resources/doc/index.md)


Installation
------------

All the installation instructions are located in
[documentation](https://github.com/jeremymarc/JmBalancedPaymentBundle/blob/master/Resources/doc/index.md).


License
-------

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE


Test
----
[![Build
Status](https://travis-ci.org/jeremymarc/JmBalancedPaymentBundle.png?branch=master)](https://travis-ci.org/jeremymarc/JmBalancedPaymentBundle)


Reporting an issue or a feature request
---------------------------------------

Issues and feature requests are tracked in the [Github issue
tracker](https://github.com/FriendsOfSymfony/JmBalancedPaymentBundle/issues).
