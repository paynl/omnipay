<?php

namespace tests;

require_once __DIR__ . '/../TestBaseOmniPay.php';

class ApproveFlowTest extends TestBaseOmniPay
{
    public function testApproveBadFlow()
    {
        $gateway = $this->getGateway();
        $gateway->setTestMode(true);
        $response = $this->getStandardOrderPurchase();
        $approveUrl = $response->getApproveUrl();

        $approveResponse = $gateway->approve(['approveUrl' => $approveUrl])->send();

        $this->assertFalse($approveResponse->isSuccessful());
        $this->assertEquals($approveResponse->getMessage(), 'PAY-2004');
    }
}