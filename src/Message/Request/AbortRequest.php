<?php

namespace Omnipay\PaynlV3\Message\Request;

use Omnipay\PaynlV3\Message\Response\AbortResponse;

class AbortRequest extends AbstractPaynlRequest
{
    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('tokenCode', 'apiSecret', 'abortUrl');
        return [
            'url' => $this->getParameter('abortUrl'),
        ];
    }

    /**
     * @param array $data
     * @return \Omnipay\Common\Message\ResponseInterface|AbortResponse
     */
    public function sendData($data)
    {
        $responseData = $this->sendRequest($data['url'], null, 'PATCH');
        return $this->response = new AbortResponse($this, $responseData);
    }

    public function getAbortUrl()
    {
        return $this->getParameter('abortUrl');
    }

    public function setAbortUrl($value)
    {
        return $this->setParameter('abortUrl', $value);
    }
}
