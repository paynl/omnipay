<?php

namespace tests;

require_once __DIR__ . '/../TestBaseOmniPay.php';

class PurchaseFlowTest extends TestBaseOmniPay
{
    public function testAddressesWontError()
    {
        $gateway = $this->getGateway();
        $gateway->setTestMode(true);

        $response = $gateway->purchase(
            [
                'amount' => '46.00',
                'currency' => 'EUR',
                'transactionReference' => 'referenceID1',
                'clientIp' => '192.168.192.12',
                'serviceId' => $this->getServiceCode(),
                'returnUrl' => 'http://www.yourdomain.com/return_from_pay',
                'card' => array(
                    'firstName' => 'Example',
                    'lastName' => 'User',
                    'gender' => 'M',
                    'birthday' => '01-02-1992',
                    'phone' => '1111111111111111',
                    'email' => 'john@example.com',
                    'country' => 'NL',

                    'shippingAddress1' => 'Shippingstreet',
                    'shippingAddress2' => 'misse',
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

        $this->assertNotEmpty($response->getTransactionReference());
    }

    public function testPurchaseResponse()
    {
        $gateway = $this->getGateway();
        $gateway->setTestMode(true);
        $response = $this->getStandardOrderPurchase();

        $this->assertNotEmpty($response->getRedirectUrl());
        $this->assertNotEmpty($response->getTransactionReference());
    }
}