<?php

namespace tests;

require_once __DIR__ . '/../TestBaseOmniPay.php';

class DeclineFlowTest extends TestBaseOmniPay
{
    public function testDeclineBadFlow()
    {
        $gateway = $this->getGateway();
        $gateway->setTestMode(true);
        $response = $this->getStandardOrderPurchase();
        $declineUrl = $response->getDeclineUrl();

        $declineResponse = $gateway->decline(['declineUrl' => $declineUrl])->send();

        $this->assertFalse($declineResponse->isSuccessful());
        $this->assertEquals($declineResponse->getMessage(), 'PAY-2005');
    }
}