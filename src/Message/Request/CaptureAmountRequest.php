<?php

namespace Omnipay\PaynlV3\Message\Request;

use Omnipay\PaynlV3\Message\Response\CaptureAmountResponse;

class CaptureAmountRequest extends AbstractPaynlRequest
{
     /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('tokenCode', 'apiSecret', 'captureAmountUrl');
        return [
            'url' => $this->getCaptureAmountUrl(),
            'amount' => $this->getAmountInteger(),
        ];
    }

    /**
     * @param array $data
     * @return \Omnipay\Common\Message\ResponseInterface|CaptureAmountResponse
     */
    public function sendData($data)
    {
        $responseData = $this->sendRequest($data['url'], $data, 'PATCH');
        return $this->response = new CaptureAmountResponse($this, $responseData);
    }

    public function getCaptureAmountUrl()
    {
        return $this->getParameter('captureAmountUrl');
    }

    public function setCaptureAmountUrl($value)
    {
        return $this->setParameter('captureAmountUrl', $value);
    }
}
