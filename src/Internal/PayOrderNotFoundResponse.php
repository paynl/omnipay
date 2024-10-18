<?php

namespace Omnipay\PaynlV3\Internal;

class PayOrderNotFoundResponse {

    /**
     * @var array|null
     */
    protected $data;
    private $orderNotFoundStatusCode = 'PAY-2001';

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return string|null
     */
    public function getCode()
    {
        return isset($this->data['code']) ? $this->data['code'] : null;
    }

    /**
     * @return string|null
     */
    public function getType()
    {
        return isset($this->data['type']) ? $this->data['type'] : null;
    }

    /**
     * @return bool
     */
    public function orderNotFoundState()
    {
        return $this->getCode() !== null && $this->getCode() === $this->orderNotFoundStatusCode ? true : false;
    }
}