<p align="center">
    <img src="https://www.pay.nl/uploads/1/brands/main_logo.png" />
</p>
<h1 align="center">Pay. Omnipay driver</h1>

# Description

Pay. driver for the Omnipay payment processing library

- [Description](#description)
- [Available payment methods](#available-payment-methods)
- [Requirements](#requirements)
- [Installation](#installation)
- [Update instructions](#update-instructions)
- [Usage](#usage)
- [Tests](#test)
- [Support](#support)


# Available payment methods

Bank Payments  | Creditcards | Gift cards & Vouchers | Pay by invoice | Others | 
:-----------: | :-----------: | :-----------: | :-----------: | :-----------: |
iDEAL + QR |Visa | VVV Cadeaukaart | AfterPay | PayPal |
Bancontact + QR |  Mastercard | Webshop Giftcard | Achteraf betalen via Billink | WeChatPay | 
Giropay |American Express | FashionCheque | Focum AchterafBetalen.nl | AmazonPay |
MyBank | Carte Bancaire | Podium Cadeaukaart | Capayable Achteraf Betalen | Cashly | 
SOFORT | PostePay | Gezondheidsbon | in3 keer betalen, 0% rente | Pay Fixed Price (phone) |
Maestro | Dankort | Fashion Giftcard | Klarna | Instore Payments (POS) |
Bank Transfer | Cartasi | GivaCard | SprayPay | Przelewy24 | 
| Tikkie | De Cadeaukaart | YourGift | Creditclick | Apple Pay | 
| Multibanco | | Paysafecard | | Payconiq
| | | Huis en Tuin Cadeau 


# Requirements

Before running the tests, make sure you have:
- PHP installed (7.4 or higher recommended)
- Composer installed
- A Pay.nl account with API credentials


# Installation
#### Installing

In command line, navigate to the installation directory of Omnipay

Enter the following command:

```
composer require league/omnipay:^3 paynl/omnipay-paynl-v3
```

The plugin is now installed


##### Setup

1. Create a new php file
2. Use the following code:

```php
# require autoloader
require_once('vendor/autoload.php');
 
use Omnipay\Omnipay;
 
# Setup payment gateway
$gateway = Omnipay::create('PaynlV3');
 
$gateway->setApiSecret('****************************************');
$gateway->setTokenCode('AT-####-####');
$gateway->setServiceId('SL-####-####');
```

3. Enter the TokenCode, API token, ServiceId (these can be found in the Pay. My Pay Panel --> https://my.pay.nl/

Go to the *Settings* / *Sales locations* tab in the Pay. Scroll down to the sales location and there copy the SL code and the secret.


#### Update instructions

In command line, navigate to the installation directory of Omnipay

Enter the following command:

```
composer update league/omnipay:^3 paynl/omnipay-paynl-v3
```

The plugin has now been updated


# Usage
### Get payment methods
```php
$response = $gateway->fetchPaymentMethods()->send();

$response->getPaymentMethods();

$paymentMethods = array();
foreach ($response->getPaymentMethods() as $paymentMethod) {
    $paymentMethods[] = [
        'id' => $paymentMethod->getId(),
        'name' => $paymentMethod->getName(),
    ];
}
```

### Get Issuers (Ideal)
```php
$response = $gateway->fetchIssuers()->send();

$response->getIssuers();

$issuers = array();
foreach ($response->getIssuers() as $issuer) {
    $issuers[] = [
        'name' => $issuer->getName(),
        'id' => $issuer->getId(),
        'paymentMethod' => $issuer->getPaymentMethod(),
    ];
}

```

### Pay. items
```php
# Use Pay. Item class
use Omnipay\Paynl\Common\Item;

# Add items to transaction
$arrItems = array();
$item = new Item();
$item->setProductId('SKU01')
        ->setProductType('ARTICLE')
        ->setVatPercentage(21)
        ->setDescription('Description')
        ->setPrice('10')
        ->setQuantity(4);
$arrItems[] = $item;

$item = new Item();
$item->setProductId('SHIP01')
        ->setProductType('SHIPPING')
        ->setVatPercentage(21)
        ->setDescription('Description')
        ->setPrice('5')
        ->setQuantity(1);
$arrItems[] = $item;

$item = new Item();
$item->setProductId('SKU02')
        ->setProductType('DISCOUNT')
        ->setVatPercentage(21)
        ->setDescription('Description')
        ->setPrice('1')
        ->setQuantity(1);
$arrItems[] = $item;
```

### Start a transaction (Order:Create)

```php
# Send purchase request
$response = $gateway->purchase(
    [
        'amount' => '46.00',
        'currency' => 'EUR',
        'transactionReference' => 'referenceID1',
        'clientIp' => '192.168.192.12',
        'returnUrl' => 'http://www.yourdomain.com/return_from_pay',
        'items' => $arrItems,
        'card' => array(
            'firstName' => 'Example',
            'lastName' => 'User',
            'gender' => 'M',
            'birthday' => '01-02-1992',
            'phone' => '1111111111111111',
            'email' => 'john@example.com',
            'country' => 'NL',

            'shippingAddress1' => 'Shippingstreet 1B',
            'shippingAddress2' => '',
            'shippingCity' => 'Shipingtown',
            'shippingPostcode' => '1234AB',
            'shippingState' => '',
            'shippingCountry' => 'NL',

            'billingFirstName' => 'Billingexample',
            'billingLastName' => 'Billinguser',
            'billingAddress1' => 'Billingstreet 1B',
            'billingAddress2' => '',
            'billingCity' => 'Billingtown',
            'billingPostcode' => '1234AB',
            'billingState' => '',
            'billingCountry' => 'NL'                     
        )
    ]
)->send();
 
# Process response
if ($response->isSuccessful()) {
    # Get the url for fetching the Transaction
    $statusUrl = $response->getStatusUrl();
    $voidUrl = $response->getVoidUrl();
    $redirectUrl = $response->getRedirectUrl();
    $givenCaptureUrl = $response->getCaptureUrl();
    $givenCaptureAmountUrl = $response->getCaptureAmountUrl();
    $givenCaptureProductsUrl = $response->getCaptureProductsUrl();
    $abortUrl = $response->getAbortUrl();
    $approveUrl = $response->getApproveUrl();
    $declineUrl = $response->getDeclineUrl();
    $debugUrl = $response->getDebugUrl();
    $checkoutUrl = $response->getCheckoutUrl();
}

    # Payment was successful
    var_dump($response);
 
} elseif ($response->isRedirect()) {
    # Get the url for fetching the Transaction
    $redirectUrl = $response->getRedirectUrl();     
} else {
    # Payment failed
    echo $response->getMessage();
}
```
### Get a transaction (Order:status)

```php
$response = $gateway->fetchTransaction(['stateUrl' => $statusUrl])->send();

if ($response->isSuccessful()) {
    # Get was successful
    print_r($response);

} else {
    # Get failed
    echo $response->getMessage();
}
```

### Approve order (Order:approve)

```php
$response = $gateway->approve(['approveUrl' => $approveUrl])->send();

if ($response->isSuccessful()) {
    # Get was successful
    print_r($response);

} else {
    # Get failed
    echo $response->getMessage();
}
```

### Approve order (Order:Decline)

```php
$response = $gateway->decline(['declineUrl' => $declineUrl])->send();

if ($response->isSuccessful()) {
    # Get was successful
    print_r($response);

} else {
    # Get failed
    echo $response->getMessage();
}
```

### Capture order (Order:Capture)

```php
$response = $gateway->capture(['captureUrl' => $captureUrl])->send();

if ($response->isSuccessful()) {
    # Get was successful
    print_r($response);

} else {
    # Get failed
    echo $response->getMessage();
}
```

### Capture amount order (Order:CaptureAmount)

```php
$response = $gateway->captureAmount([
    'captureAmountUrl' => $captureAmountUrl, 
    'amount' => '14.00'
])->send();

if ($response->isSuccessful()) {
    # Get was successful
    print_r($response);

} else {
    # Get failed
    echo $response->getMessage();
}
```

### Capture products order (Order:CaptureProducts)

```php
$arrItems = array();
$item = new Item();
$item->setProductId('SKU01')
        ->setProductType('ARTICLE')
        ->setVatPercentage(21)
        ->setDescription('Description')
        ->setPrice('10')
        ->setQuantity(4);

$arrItems[] = $item;

$response = $gateway->captureProducts([
    'captureAmountUrl' => $captureProductsUrl, 
    'items' => $arrItems
])->send();

if ($response->isSuccessful()) {
    # Get was successful
    print_r($response);

} else {
    # Get failed
    echo $response->getMessage();
}
```

### Capture products order (Order:Abort)

```php
$response = $gateway->abort([
    'abortUrl' => $abortUrl
])->send();

if ($response->isSuccessful()) {
    # Get was successful
    print_r($response);

} else {
    # Get failed
    echo $response->getMessage();
}
```

### Void a transaction

```php
$response = $gateway->void(['voidUrl' => $voidUrl])->send();

if ($response->isSuccessful()) {
    # Get was successful
    print_r($response);

} else {
    # Get failed
    echo $response->getMessage();
}
```

# Test

Before running the tests, make sure you have:
- PHP installed (7.4 or higher recommended)
- Composer installed
- A Pay.nl account with API credentials

## Setup

1. Install dependencies:
```bash
composer install
```

2. Create a copy of the phpunit configuration file:
```bash
cp phpunit.xml.dist phpunit.xml
```

3. Configure environment variables in your `phpunit.xml`:

Replace the empty values with your Pay.nl credentials:

> **Note**: Never commit your actual API credentials to version control. The `phpunit.xml` file should be in your `.gitignore`.

## Running Tests

To run all the tests:
```bash
./vendor/bin/phpunit --testsuite "Omni pay v3 tests"
```

## Environment Variables

| Variable | Description                                      |
|----------|--------------------------------------------------|
| `PAYNL_SERVICE_CODE` | Your Pay.nl service location code (SL-xxxx-xxxx) |
| `PAYNL_API_SECRET` | Your Pay.nl API token                            |

# Support
https://www.pay.nl

Contact us: support@pay.nl
