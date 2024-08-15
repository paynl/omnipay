<?php

namespace Omnipay\PaynlV3\Message\Request;


use Omnipay\PaynlV3\Message\Response\FetchPaymentMethodsResponse;

/**
 * Class FetchPaymentMethodsRequest
 * @package Omnipay\Paynl\Message\Request
 *
 * @method FetchPaymentMethodsResponse send()
 */
class FetchPaymentMethodsRequest extends AbstractPaynlRequest
{
    public function getData()
    {
        $this->validate('tokenCode', 'apiSecret', 'serviceId');

        return [
            'serviceId' => $this->getServiceId(),
        ];
    }

    public function sendData($data)
    {
        $responseData = $this->sendRequestRestApi('services/config?serviceId='.$data['serviceId']);

        return $this->response = new FetchPaymentMethodsResponse($this, $responseData);
    }
}