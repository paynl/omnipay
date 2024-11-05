<?php

namespace Omnipay\PaynlV3;

use InvalidArgumentException;
use Omnipay\Common\AbstractGateway;
use Omnipay\PaynlV3\Message\Request\AbortRequest;
use Omnipay\PaynlV3\Message\Request\ApproveRequest;
use Omnipay\PaynlV3\Message\Request\CaptureAmountRequest;
use Omnipay\PaynlV3\Message\Request\CaptureProductsRequest;
use Omnipay\PaynlV3\Message\Request\CaptureRequest;
use Omnipay\PaynlV3\Message\Request\DeclineRequest;
use Omnipay\PaynlV3\Message\Request\FetchIssuersRequest;
use Omnipay\PaynlV3\Message\Request\FetchPaymentMethodsRequest;
use Omnipay\PaynlV3\Message\Request\FetchServiceConfigRequest;
use Omnipay\PaynlV3\Message\Request\FetchTransactionRequest;
use Omnipay\PaynlV3\Message\Request\PurchaseRequest;
use Omnipay\PaynlV3\Message\Request\VoidRequest;

class Gateway extends AbstractGateway
{
    /**
     * @return string
     */
    public function getName(): string
    {
       return 'PaynlV3';
    }

    public function getDefaultParameters()
    {
        return [
            'tokenCode' => null,
            'apiSecret' => null,
            'serviceId' => null,
            'coreDomains' => array('pay.nl'),
        ];
    }

    /**
     * @param string $value Example: AT-1234-5678 | SL-1234-5678
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
    public function getApiSecret()
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
     * @param string[]|null $value Example: ['pay.nl']
     * @return $this
     * @throws InvalidArgumentException If array contains non-string values
     */
    public function setCoreDomains(?array $value)
    {
        if ($value !== null) {
            foreach ($value as $domain) {
                if (!is_string($domain)) {
                    throw new InvalidArgumentException('All core domains must be strings');
                }
            }
        }
        $this->setParameter('coreDomains', $value);
        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getCoreDomains()
    {
        return $this->getParameter('coreDomains');
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
     * @return \Omnipay\Common\Message\AbstractRequest|ApproveRequest
     */
    public function approve(array $options = array())
    {
        return $this->createRequest(ApproveRequest::class, $options);
    }

    /**
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest|AbortRequest
     */
    public function abort(array $options = array())
    {
        return $this->createRequest(AbortRequest::class, $options);
    }

    /**
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest|DeclineRequest
     */
    public function decline(array $options = array())
    {
        return $this->createRequest(DeclineRequest::class, $options);
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
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest|CaptureAmountRequest
     */
    public function captureAmount(array $options = array())
    {
        return $this->createRequest(CaptureAmountRequest::class, $options);
    }

    /**
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest|CaptureProductsRequest
     */
    public function captureProducts(array $options = array())
    {
        return $this->createRequest(CaptureProductsRequest::class, $options);
    }

    /**
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest|FetchServiceConfigRequest
     */
    public function fetchServiceConfig(array $options = array())
    {
        return $this->createRequest(FetchServiceConfigRequest::class, $options);
    }
}