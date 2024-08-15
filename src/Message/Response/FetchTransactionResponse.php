<?php

namespace Omnipay\PaynlV3\Message\Response;

class FetchTransactionResponse extends AbstractPaynlResponseWithLinks
{
    /**
     * @return bool
     */
    public function isCancelled()
    {
        return isset($this->data['status']['action']) && 'CANCEL' === $this->data['status']['action'];
    }

    /**
     * @return bool
     */
    public function isPending()
    {
        return
            isset($this->data['status']['action']) &&
            (strpos('PENDING', strtoupper($this->data['status']['action'])) !== false);
    }

    /**
     * @return bool
     */
    public function isOpen()
    {
        return $this->isPending();
    }

    /**
     * @return bool
     */
    public function isVerify()
    {
        return
            isset($this->data['status']['action']) &&
            strtoupper($this->data['status']['action']) == 'VERIFY';
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        return isset($this->data['status']['action']) && 'EXPIRED' === $this->data['status']['action'];
    }

    /**
     * @return null|string
     */
    public function getTransactionReference()
    {
        return isset($this->data['id']) ? $this->data['id'] : null;
    }

    /**
     * @return null|string
     */
    public function getStatus()
    {
        return isset($this->data['status']['action']) ? $this->data['status']['action'] : null;
    }

    /**
     * @return float|null
     */
    public function getAmount()
    {
        return isset($this->data['amount']['value']) ? $this->data['amount']['value'] / 100 : null;
    }

    /**
     * @return string|null The paid currency
     */
    public function getCurrency()
    {
        return isset($this->data['amount']['currency']) ? $this->data['amount']['currency'] : null;
    }

    /**
     * @return boolean
     */
    public function isPaid()
    {
        return isset($this->data['status']['action']) && in_array($this->data['status']['action'],
                array('PAID', 'AUTHORIZE'));
    }

    /**
     * @return boolean
     */
    public function isAuthorized()
    {
        return isset($this->data['status']['action']) && $this->data['status']['action'] == 'AUTHORIZE';
    }
}
