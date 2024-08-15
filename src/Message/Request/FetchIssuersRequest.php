<?php

namespace Omnipay\PaynlV3\Message\Request;

use Omnipay\PaynlV3\Message\Response\FetchIssuersResponse;

/**
 * Class FetchIssuersRequest
 * @package Omnipay\Paynl\Message\Request
 *
 * @method FetchIssuersResponse send()
 */
class FetchIssuersRequest extends AbstractPaynlRequest
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

        return $this->response = new FetchIssuersResponse($this, $responseData);
    }
}