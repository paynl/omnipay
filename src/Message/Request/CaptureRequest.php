<?php
namespace Omnipay\PaynlV3\Message\Request;

use Omnipay\PaynlV3\Message\Response\CaptureResponse;

class CaptureRequest extends AbstractPaynlRequest
{
     /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('tokenCode', 'apiSecret', 'captureUrl');
        return [
            'url' =>   $this->getParameter('captureUrl'),
        ];
    }

    /**
     * @param array $data
     * @return \Omnipay\Common\Message\ResponseInterface|CaptureResponse
     */
    public function sendData($data)
    {
        $responseData = $this->sendRequest($data['url'], null, 'PATCH');
        return $this->response = new CaptureResponse($this, $responseData);
    }

    public function getCaptureUrl()
    {
        return $this->getParameter('captureUrl');
    }

    public function setCaptureUrl($value)
    {
        return $this->setParameter('captureUrl', $value);
    }
}
