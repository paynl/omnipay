<?php

namespace Omnipay\PaynlV3\Message\Response;

abstract class AbstractPaynlResponseWithLinks extends AbstractPaynlResponse
{
    /**
     * @return null|string
     */
    public function getStatusUrl()
    {
        return isset($this->data['links']['status']) ? $this->data['links']['status'] : null;
    }

    /**
     * @return null|string
     */
    public function getAbortUrl()
    {
        return isset($this->data['links']['abort']) ? $this->data['links']['abort'] : null;
    }

    /**
     * @return null|string
     */
    public function getApproveUrl()
    {
        return isset($this->data['links']['approve']) ? $this->data['links']['approve'] : null;
    }

    /**
     * @return null|string
     */
    public function getDeclineUrl()
    {
        return isset($this->data['links']['decline']) ? $this->data['links']['decline'] : null;
    }

    /**
     * @return null|string
     */
    public function getVoidUrl()
    {
        return isset($this->data['links']['void']) ? $this->data['links']['void'] : null;
    }

    /**
     * @return null|string
     */
    public function getCaptureUrl()
    {
        return isset($this->data['links']['capture']) ? $this->data['links']['capture'] : null;
    }

    /**
     * @return null|string
     */
    public function getCaptureAmountUrl()
    {
        return isset($this->data['links']['captureAmount']) ? $this->data['links']['captureAmount'] : null;
    }

    /**
     * @return null|string
     */
    public function getCaptureProductsUrl()
    {
        return isset($this->data['links']['captureProducts']) ? $this->data['links']['captureProducts'] : null;
    }

    /**
     * @return null|string
     */
    public function getDebugUrl()
    {
        return isset($this->data['links']['debug']) ? $this->data['links']['debug'] : null;
    }

    /**
     * @return null|string
     */
    public function getCheckoutUrl()
    {
        return isset($this->data['links']['checkout']) ? $this->data['links']['checkout'] : null;
    }

    /**
     * @return null|string
     */
    public function getRedirectUrl()
    {
        return isset($this->data['links']['redirect']) ? $this->data['links']['redirect'] : null;
    }
}