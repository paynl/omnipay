<?php

namespace Omnipay\PaynlV3\Message\Request;

use Omnipay\PaynlV3\Message\Response\RefundResponse;

class RefundRequest extends AbstractPaynlRequest
{
    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('tokenCode', 'apiSecret', 'transactionReference');

        $data = [
            'id' => $this->getTransactionReference(),
        ];

        return $data;
    }

    /**
     * @param array $data
     * @return \Omnipay\Common\Message\ResponseInterface|RefundResponse
     */
    public function sendData($data)
    {
        $responseData = $this->sendRequestRestApi('transactions/' . $data['id'] . '/refund', method: 'PATCH');
        return $this->response = new RefundResponse($this, $responseData);
    }
}