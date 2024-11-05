<?php
namespace Omnipay\PaynlV3\Message\Request;

use Omnipay\PaynlV3\Message\Response\FetchTransactionResponse;

class FetchTransactionRequest extends AbstractPaynlRequest
{
    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('tokenCode', 'apiSecret', 'stateUrl');

        return [
            'url' => $this->getParameter('stateUrl')
        ];
    }

    /**
     * @param array $data
     * @return \Omnipay\Common\Message\ResponseInterface|FetchTransactionResponse
     */
    public function sendData($data)
    {
        $responseData = $this->sendRequest($data['url']);
        return $this->response = new FetchTransactionResponse($this, $responseData);
    }

    public function getStateUrl()
    {
        return $this->getParameter('stateUrl');
    }

    public function setStateUrl($value)
    {
        return $this->setParameter('stateUrl', $value);
    }
}
