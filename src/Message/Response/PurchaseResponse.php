<?php

namespace Omnipay\PaynlV3\Message\Response;
use Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends AbstractPaynlResponseWithLinks implements RedirectResponseInterface
{
    /**
     * When you do a `purchase` the request is never successful because
     * you need to redirect off-site to complete the purchase.
     *
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isRedirect()
    {
        return isset($this->data['links']['redirect']);
    }

    /**
     * @returns bool The order is created
     */
    public function isSuccessfulCreated()
    {
        return isset($this->data['id']) ? true : false;

    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectData()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getTransactionReference()
    {
        return isset($this->data['id']) ? $this->data['id'] : null;
    }

    /**
     * @return string|null The payment accept code used to identify bank transfers
     */
    public function getAcceptCode()
    {
        return isset($this->data['reference']) ? $this->data['reference'] : null;
    }
}
