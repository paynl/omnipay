<?php

namespace Omnipay\PaynlV3\Message\Response;


use Omnipay\Common\PaymentMethod;

class FetchPaymentMethodsResponse extends AbstractPaynlResponse
{
    /**
     * Return available payment methods as an array
     *
     * @return PaymentMethod[]|null
     */
    public function getPaymentMethods()
    {
        if (!isset($this->data['checkoutOptions']) || empty($this->data['checkoutOptions'])) {
            return null;
        }
        $paymentMethods = [];
        foreach ($this->data['checkoutOptions'] as $method) {
            $paymentMethods[] = new PaymentMethod($method['paymentMethods'][0]['id'], $method['paymentMethods'][0]['name']);
        }

        return $paymentMethods;
    }
}