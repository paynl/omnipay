<?php

namespace Omnipay\PaynlV3\Message\Response;


use Omnipay\Common\Issuer;

class FetchIssuersResponse extends AbstractPaynlResponse
{
    /**
     * @inheritdoc
     */
    public function isSuccessful()
    {
        return isset($this->data) && is_array($this->data) && !empty($this->data);
    }

    /**
     * @return Issuer[]|null
     */
    public function getIssuers()
    {
        $issuers = [];
        if (empty($this->data) || !is_array($this->data)) return null;

        $idealOptionKey = array_search('PM_10', array_column($this->data['checkoutOptions'], 'tag'));

        foreach ($this->data['checkoutOptions'][$idealOptionKey]['paymentMethods'][0]['options'] as $issuer) {

            $issuers[] = new Issuer($issuer['id'], $issuer['name'], '10');
        }
        return $issuers;
    }
}