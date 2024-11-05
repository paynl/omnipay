<?php

namespace tests;

require_once __DIR__ . '/../TestBaseOmniPay.php';

class ServiceConfigFlowTest extends TestBaseOmniPay
{
    public function testServiceConfigFlowCorrect()
    {
        $gateway = $this->getGateway();
        $gateway->setTestMode(true);
        $response = $gateway->fetchServiceConfig()->send();

        $this->assertNotEmpty($response->getActiveTguList());
        $this->assertIsArray($response->getActiveTguList());
    }
}