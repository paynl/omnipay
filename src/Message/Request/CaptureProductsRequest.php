<?php
namespace Omnipay\PaynlV3\Message\Request;

use Omnipay\PaynlV3\Common\Item;
use Omnipay\PaynlV3\Message\Response\CaptureProductsResponse;

class CaptureProductsRequest extends AbstractPaynlRequest
{
     /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('tokenCode', 'apiSecret', 'captureProductsUrl', 'items');
        $data = [
            'url' => $this->getCaptureProductsUrl(),
            'products' => array()
        ];

        if ($items = $this->getItems()) {
            $data['products'] = array_map(function ($item) {
                /** @var Item | \Omnipay\Common\Item $item */
                if (method_exists($item, 'getProductId')) {
                    $productId = $item->getProductId();
                } else {
                    $productId = substr($item->getName(), 0, 25);
                }

                return [
                    'id' => $productId,
                    'quantity' => $item->getQuantity(),
                ];
            }, $items);
        }

        return $data;
    }

    /**
     * @param array $data
     * @return \Omnipay\Common\Message\ResponseInterface|CaptureProductsResponse
     */
    public function sendData($data)
    {
        $postObject = $data;
        $responseData = $this->sendRequest($data['url'], $postObject, 'PATCH');
        return $this->response = new CaptureProductsResponse($this, $responseData);
    }

    public function getCaptureProductsUrl()
    {
        return $this->getParameter('captureProductsUrl');
    }

    public function setCaptureProductsUrl($value)
    {
        return $this->setParameter('captureProductsUrl', $value);
    }

    public function getItems()
    {
        return $this->getParameter('items');
    }

    public function setItems($value)
    {
        return $this->setParameter('items', $value);
    }
}
