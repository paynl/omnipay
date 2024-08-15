<?php
namespace Omnipay\PaynlV3\Message\Request;

use Omnipay\PaynlV3\Common\Item;
use Omnipay\PaynlV3\Message\Response\CaptureResponse;

class CaptureRequest extends AbstractPaynlRequest
{
     /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('tokenCode', 'apiSecret', 'transactionReference');
        $data = [
            'id' =>   $this->getParameter('transactionReference'),
            'amount' => $this->getAmountInteger(),
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
            }, $items->all());
        }

        return $data;
    }

    /**
     * @param array $data
     * @return \Omnipay\Common\Message\ResponseInterface|CaptureResponse
     */
    public function sendData($data)
    {
        $response = null;

        if (isset($data['products']) && count($data['products']) > 0) {
            $url = '/' . $data['id'] . '/capture/products';
            $postObject = ['products' => $data['products']];
            $responseData = $this->sendRequestMultiCore($url, $postObject,'PATCH');
        }

        if (isset($data['amount']) && $data['amount'] > 0) {
            $url = '/' . $data['id'] . '/capture/amount';
            $postObject = ['amount' => $data['amount']];
            $responseData = $this->sendRequestMultiCore($url, $postObject,'PATCH');
        }

        return $this->response = new CaptureResponse($this, $responseData);
    }
}
