# Jvzoo Rest API (PHP)

You can use this class to interface with the JVZoo Rest API.

# Install (Composer)

```
composer require nsanden/jvzoo-rest-api-php:dev-master
```

# Examples

Start with
```php
$api_key = 'xxxxxxxxx';
$account_password = 'xxxxxxxx';
$jvzoo_rest_api = new \nsanden\jvzoo\JvzooRestApi($api_key, $account_password);
```
Get recurring payment status
```php
$pre_key = 'PA-XXXXXXXXXXXX';
var_dump(json_decode($jvzoo_rest_api->getRecurringPayment($pre_key)));
```
Cancel recurring payment
```php
$pre_key = 'PA-XXXXXXXXXXXX';
var_dump(json_decode($jvzoo_rest_api->cancelRecurringPayment($pre_key)));
```
Get transaction summary
```php
$pay_key = 'PA-XXXXXXXXXXXX';
var_dump(json_decode($jvzoo_rest_api->getTransactionSummary($pay_key)));
```
Get affiliate status
```php
$product_id = 'XXXXXXXX';
$affiliate_id = 'XXXXXXXXXXXX';
var_dump(json_decode($jvzoo_rest_api->getAffiliateStatus($product_id, $affiliate_id)));
```
