<?php

namespace tests;

use Omnipay\PaynlV3\Common\Item;

require_once __DIR__ . '/../TestBaseOmniPay.php';

class CaptureFlowTest extends TestBaseOmniPay
{
    public function testCaptureRegularBadFlow()
    {
        $gateway = $this->getGateway();
        $gateway->setTestMode(true);
        $response = $this->getStandardOrderPurchase();
        $captureUrl = $response->getCaptureUrl();

        $captureResponse = $gateway->capture(['captureUrl' => $captureUrl])->send();

        $this->assertFalse($captureResponse->isSuccessful());
        $this->assertEquals('PAY-2008', $captureResponse->getMessage());
    }

    public function testCaptureAmountRegularBadFlow()
    {
        $gateway = $this->getGateway();
        $gateway->setTestMode(true);
        $response = $this->getStandardOrderPurchase();
        $captureAmountUrl = $response->getCaptureAmountUrl();

        $captureResponse = $gateway->captureAmount(['captureAmountUrl' => $captureAmountUrl, 'amount' => '5.00'])->send();

        $this->assertFalse($captureResponse->isSuccessful());
        $this->assertEquals('PAY-2008', $captureResponse->getMessage());
    }

    public function testCaptureProductRegularBadFlow()
    {
        $gateway = $this->getGateway();
        $gateway->setTestMode(true);
        $response = $this->getStandardOrderPurchase();
        $captureProductsUrl = $response->getCaptureProductsUrl();

        $arrItems = array();
        $item = new Item();
        $item->setProductId('SKU01')
            ->setProductType('ARTICLE')
            ->setVatPercentage(21)
            ->setDescription('Description')
            ->setPrice('10')
            ->setQuantity(4);
        $arrItems[] = $item;

        $captureResponse = $gateway->captureProducts(['captureProductsUrl' => $captureProductsUrl, 'items' => $arrItems])->send();

        $this->assertFalse($captureResponse->isSuccessful());
        $this->assertEquals('PAY-2008', $captureResponse->getMessage());
    }

    public function testCaptureProductNoProductsBadFlow()
    {
        $gateway = $this->getGateway();
        $gateway->setTestMode(true);
        $response = $this->getStandardOrderPurchase();
        $captureProductsUrl = $response->getCaptureProductsUrl();

        $captureResponse = $gateway->captureProducts(['captureProductsUrl' => $captureProductsUrl, 'items' => []])->send();

        $this->assertFalse($captureResponse->isSuccessful());
        $this->assertEquals('PAY-1422', $captureResponse->getMessage());
    }
}
