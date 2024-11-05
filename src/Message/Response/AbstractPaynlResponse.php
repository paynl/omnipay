<?php

namespace Omnipay\PaynlV3\Message\Response;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\PaynlV3\Message\Request\AbstractPaynlRequest;

abstract class AbstractPaynlResponse extends AbstractResponse
{
    /**
     * @var AbstractPaynlRequest
     */
    protected $request;

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return isset($this->data['id']) ? true : false;
    }

    /**
     * @return integer
     */
    public function getOrderStateCode()
    {
        return $this->data['status']['code'];
    }

    /**
     * @return string
     */
    public function getOrderState()
    {
        return $this->data['status']['action'];
    }

    /**
     * @return null|string The error message
     */
    public function getDetails()
    {
        return isset($this->data['detail']) && !empty($this->data['detail']) ? $this->data['detail'] : null;
    }

    /**
     * @return null|string The error message
     */
    public function getMessage()
    {
        return isset($this->data['code']) && !empty($this->data['code']) ? $this->data['code'] : null;
    }
}
