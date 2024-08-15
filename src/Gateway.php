<?php

namespace Omnipay\PaynlV3;

use Omnipay\Common\AbstractGateway;
use Omnipay\PaynlV3\Message\Request\CaptureRequest;
use Omnipay\PaynlV3\Message\Request\CompletePurchaseRequest;
use Omnipay\PaynlV3\Message\Request\FetchIssuersRequest;
use Omnipay\PaynlV3\Message\Request\FetchPaymentMethodsRequest;
use Omnipay\PaynlV3\Message\Request\FetchTransactionRequest;
use Omnipay\PaynlV3\Message\Request\PurchaseRequest;
use Omnipay\PaynlV3\Message\Request\RefundRequest;
use Omnipay\PaynlV3\Message\Request\VoidRequest;

class Gateway extends AbstractGateway
{
    public function getName()
    {
       return 'PaynlV3';
    }

    public function getDefaultParameters()
    {
        return [
            'tokenCode' => null,
            'apiSecret' => null,
            'serviceId' => null,
        ];
    }

    /**
     * @param string $value Example: AT-1234-5678
     * @return $this
     */
    public function setTokenCode($value)
    {
        $this->setParameter('tokenCode', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getTokenCode()
    {
        return $this->getParameter('tokenCode');
    }

    /**
     * @param string $value Your API secret
     * @return $this
     */
    public function setApiSecret($value)
    {
        $this->setParameter('apiSecret', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getApiToken()
    {
        return $this->getParameter('apiSecret');
    }

    /**
     * @param string $value Example: SL-1234-5678
     * @return $this
     */
    public function setServiceId($value)
    {
        $this->setParameter('serviceId', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getServiceId()
    {
        return $this->getParameter('serviceId');
    }

    /**
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest|FetchTransactionRequest
     */
    public function fetchTransaction(array $options = [])
    {
        return $this->createRequest(FetchTransactionRequest::class, $options);
    }

    /**
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest|FetchPaymentMethodsRequest
     */
    public function fetchPaymentMethods(array $options = [])
    {
        return $this->createRequest(FetchPaymentMethodsRequest::class, $options);
    }

    /**
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest|FetchIssuersRequest
     */
    public function fetchIssuers(array $options = [])
    {
        return $this->createRequest(FetchIssuersRequest::class, $options);
    }

    /**
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest|PurchaseRequest
     */
    public function purchase(array $options = array())
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    /**
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest|VoidRequest
     */
    public function void(array $options = array())
    {
        return $this->createRequest(VoidRequest::class, $options);
    }

    /**
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest|CaptureRequest
     */
    public function capture(array $options = array())
    {
        return $this->createRequest(CaptureRequest::class, $options);
    }

    /**
     * This endpoint is resolving to the old API (rest v2 api: https://developer.pay.nl/v2.0/reference/patch_transactions-transactionid-refund)
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest|RefundRequest
     */
    public function refund(array $options = array())
    {
        return $this->createRequest(RefundRequest::class, $options);
    }

    /**
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest|CompletePurchaseRequest
     */
    public function completePurchase(array $options = array())
    {
        return $this->createRequest(CompletePurchaseRequest::class, $options);
    }
}