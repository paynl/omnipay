<?php

namespace Omnipay\PaynlV3\Message\Response;

class VoidResponse extends AbstractPaynlResponseWithLinks
{
    /**
     * @return null|string
     */
    public function getTransactionReference()
    {
        return isset($this->data['id']) ? $this->data['id'] : null;
    }
}