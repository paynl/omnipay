<?php


namespace Omnipay\PaynlV3\Message\Request;

use Omnipay\PaynlV3\Message\Response\ApproveResponse;

/**
 * Class ApproveRequest
 * @package Omnipay\Paynl\Message\Request
 *
 * @method ApproveResponse send()
 */
class ApproveRequest extends AbstractPaynlRequest
{
    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('tokenCode', 'apiSecret', 'approveUrl');

        return [
            'url' => $this->getParameter('approveUrl')
        ];
    }

    /**
     * @param array $data
     * @return \Omnipay\Common\Message\ResponseInterface|ApproveResponse
     */
    public function sendData($data)
    {
        $responseData = $this->sendRequest($data['url'], method: 'PATCH');
        return $this->response = new ApproveResponse($this, $responseData);
    }

    public function getApproveUrl()
    {
        return $this->getParameter('approveUrl');
    }

    public function setApproveUrl($value)
    {
        return $this->setParameter('approveUrl', $value);
    }
}