<?php


namespace Omnipay\PaynlV3\Message\Request;

use Omnipay\PaynlV3\Message\Response\VoidResponse;

/**
 * Class VoidRequest
 * @package Omnipay\Paynl\Message\Request
 *
 * @method VoidResponse send()
 */
class VoidRequest extends AbstractPaynlRequest
{
    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('tokenCode', 'apiSecret', 'voidUrl');

        return [
            'url' => $this->getParameter('voidUrl')
        ];
    }

    /**
     * @param array $data
     * @return \Omnipay\Common\Message\ResponseInterface|VoidResponse
     */
    public function sendData($data)
    {
        $responseData = $this->sendRequest($data['url'], method: 'PATCH');
        return $this->response = new VoidResponse($this, $responseData);
    }

    public function getVoidUrl()
    {
        return $this->getParameter('voidUrl');
    }

    public function setVoidUrl($value)
    {
        return $this->setParameter('voidUrl', $value);
    }
}