<?php

namespace tests;

require_once __DIR__ . '/../TestBaseOmniPay.php';

class FetchTransactionTest extends TestBaseOmniPay
{
    public function testPurchaseResponseAndGetState()
    {
        $gateway = $this->getGateway();
        $gateway->setTestMode(true);
        $response = $this->getStandardOrderPurchase();
        $statusUrl = $response->getStatusUrl();
        $this->assertNotEmpty($statusUrl);

        $stateResponse = $gateway->fetchTransaction(['stateUrl' => $statusUrl])->send();

        $this->assertTrue($stateResponse->isPending());
    }
}