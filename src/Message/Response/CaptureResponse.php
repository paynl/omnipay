<?php

namespace Omnipay\PaynlV3\Message\Response;

class CaptureResponse  extends AbstractPaynlResponse
{
    /**
     * @return null|string
     */
    public function getTransactionReference()
    {
        return $this->request->getTransactionReference();
    }
}