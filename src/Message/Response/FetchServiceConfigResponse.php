<?php

namespace Omnipay\PaynlV3\Message\Response;

use Omnipay\Common\Message\RequestInterface;

class FetchServiceConfigResponse {

    protected RequestInterface $request;
    protected mixed $data;

    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data = $data;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return array|mixed
     */
    public function getActiveTguList()
    {
        $array = isset($this->data['tguList']) ? $this->data['tguList'] : [];
        return array_filter($array, function ($item) {
            return $item['status'] === 'ACTIVE';
        });
    }
}