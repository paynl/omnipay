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
        $this->validate('tokenCode', 'apiSecret', 'transactionReference');

        return [
            'id' => $this->getParameter('transactionReference')
        ];
    }

    /**
     * @param array $data
     * @return \Omnipay\Common\Message\ResponseInterface|VoidResponse
     */
    public function sendData($data)
    {
        $voidUrl = '/' . $data['id'] . '/void';

        $responseData = $this->sendRequestMultiCore($voidUrl, method: 'PATCH');
        return $this->response = new VoidResponse($this, $responseData);
    }
}