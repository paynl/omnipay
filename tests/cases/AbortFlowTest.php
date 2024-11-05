<?php

namespace tests;

require_once __DIR__ . '/../TestBaseOmniPay.php';

class AbortFlowTest extends TestBaseOmniPay
{
    public function testAbortFlowCorrect()
    {
        $gateway = $this->getGateway();
        $gateway->setTestMode(true);
        $response = $this->getStandardOrderPurchase();
        $abortUrl = $response->getAbortUrl();
        $this->assertNotEmpty($abortUrl);

        $stateResponse = $gateway->abort(['abortUrl' => $abortUrl])->send();

        $this->assertTrue($stateResponse->isSuccessful());
        $this->assertEquals($stateResponse->getOrderStateCode(), -90);
        $this->assertEquals($stateResponse->getOrderState(), 'CANCEL');
    }
}