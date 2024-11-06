<?php

namespace tests;

require_once __DIR__ . '/../TestBaseOmniPay.php';

class VoidFlowTest extends TestBaseOmniPay
{
    public function testVoidBadFlow()
    {
        $gateway = $this->getGateway();
        $gateway->setTestMode(true);
        $response = $this->getStandardOrderPurchase();
        $voidUrl = $response->getVoidUrl();
        $this->assertNotEmpty($voidUrl);

        $voidResponse = $gateway->void(['voidUrl' => $voidUrl])->send();

        $this->assertFalse($voidResponse->isSuccessful());
        $this->assertEquals('PAY-2009', $voidResponse->getMessage());
    }
}
