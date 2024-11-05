<?php

namespace tests;

use Dotenv\Dotenv;
use Omnipay\Omnipay;
use PHPUnit\Framework\TestCase;

abstract class TestBaseOmniPay extends TestCase
{
    private $serviceCode;
    private $serviceSecret;
    private $gateway;

    protected function setUp(): void
    {
        parent::setUp();

        // Load from environment variables
        $this->serviceCode = $_ENV['PAYNL_SERVICE_CODE'] ?? '';
        $this->serviceSecret = $_ENV['PAYNL_API_SECRET'] ?? '';

        // Verify required environment variables are set
        if (empty($this->serviceCode) || empty($this->serviceSecret)) {
            $this->markTestSkipped('Missing required environment variables PAYNL_SERVICE_CODE and/or PAYNL_API_SECRET');
        }

        $this->gateway = Omnipay::create('PaynlV3');

        $this->gateway->setTokenCode($this->getServiceCode());
        $this->gateway->setApiSecret($this->getServiceSecret());
    }

    public function getServiceCode(): string
    {
        return $this->serviceCode;
    }

    public function getServiceSecret(): string
    {
        return $this->serviceSecret;
    }

    public function getGateway()
    {
        return $this->gateway;
    }

    public function getStandardOrderPurchase()
    {
        $form = [
            'amount' => '10.00',
            'serviceId' => $this->getServiceCode(),
            'returnUrl' => 'https://pay.nl',
            'transactionReference' => 'automated tests',
        ];

        $response = $this->gateway->purchase($form)->send();
        return $response;
    }
}