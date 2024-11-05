<?php


namespace Omnipay\PaynlV3\Message\Request;

use Omnipay\PaynlV3\Message\Response\DeclineResponse;

/**
 * Class DeclineRequest
 * @package Omnipay\Paynl\Message\Request
 *
 * @method DeclineResponse send()
 */
class DeclineRequest extends AbstractPaynlRequest
{
    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('tokenCode', 'apiSecret', 'declineUrl');

        return [
            'url' => $this->getParameter('declineUrl')
        ];
    }

    /**
     * @param array $data
     * @return \Omnipay\Common\Message\ResponseInterface|DeclineResponse
     */
    public function sendData($data)
    {
        $responseData = $this->sendRequest($data['url'], method: 'PATCH');
        return $this->response = new DeclineResponse($this, $responseData);
    }

    public function getDeclineUrl()
    {
        return $this->getParameter('declineUrl');
    }

    public function setDeclineUrl($value)
    {
        return $this->setParameter('declineUrl', $value);
    }
}