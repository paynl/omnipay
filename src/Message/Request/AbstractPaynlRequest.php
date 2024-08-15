<?php

namespace Omnipay\PaynlV3\Message\Request;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\PaynlV3\Internal\PayOrderNotFoundResponse;
use Omnipay\PaynlV3\Message\Response\FetchServiceConfigResponse;

/**
 * Class OrderAbstractPaynlRequest
 * @package Omnipay\Paynl\Message\Request
 */
abstract class AbstractPaynlRequest extends AbstractRequest
{
    /**
     * @var string
     */
    private $restUri = 'https://rest.pay.nl/v2/';
    private $baseUrlPrefix = 'https://connect.';
    private $version = 'v1/orders';


    /**
     * @param string $endpoint
     * @param array|null $data
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function sendRequestMultiCore($endpoint, array $data = null, string $method = 'GET', $fetchConfig = false)
    {
        $configResponse = $this->sendRequestRestApi('services/config');

        $decodedResponse = new FetchServiceConfigResponse($this, $configResponse);
        $jsonErrorResponse = null;

        $headers = $this->getAuthHeader();
        $headers += ['Content-Type' => 'application/json', 'accept' => 'application/json'];

        foreach ($decodedResponse->getActiveTguList() as $activeTgu)
        {
            $url = $this->baseUrlPrefix . $activeTgu['domain'] . '/' . $this->version . $endpoint;
            $body = null;
            if (!is_null($data)) {
                $body = json_encode($data);
            }

            $response = $this->httpClient->request($method, $url, $headers, $body);

            if ($response->getStatusCode() === 404) {
                $jsonErrorResponse = json_decode($response->getBody(), true);
                $decodedErrorResponse = new PayOrderNotFoundResponse($jsonErrorResponse);
                if ($decodedErrorResponse->orderNotFoundState()) {
                   continue;
                }

            }

            return json_decode($response->getBody(), true);
        }

        return $jsonErrorResponse;
    }

    public function sendRequestRestApi($endpoint, array $data = null, string $method = 'GET') {
        $headers = $this->getAuthHeader();
        $headers += ['Content-Type' => 'application/json', 'accept' => 'application/json'];

        $url = $this->restUri . $endpoint;
        $body = null;
        if (!is_null($data)) {
            $body = json_encode($data);
        }

        $response = $this->httpClient->request($method, $url, $headers, $body);
        return json_decode($response->getBody(), true);
    }

    /**
     * @return array
     */
    private function getAuthHeader()
    {
        if (!$this->getApiSecret() && !$this->getTokenCode()) {
            return [];
        }

        return [
            'Authorization' => 'Basic ' .
                base64_encode($this->getTokenCode() . ':' . $this->getApiSecret())
        ];
    }

    /**
     * @return string
     */
    public function getTokenCode()
    {
        return $this->getParameter('tokenCode');
    }

    /**
     * @return string
     */
    public function getApiSecret()
    {
        return $this->getParameter('apiSecret');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setTokenCode($value)
    {
        return $this->setParameter('tokenCode', $value);
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setApiSecret($value)
    {
        return $this->setParameter('apiSecret', $value);
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setServiceId($value)
    {
        return $this->setParameter('serviceId', $value);
    }

    /**
     * @return string
     */
    public function getServiceId()
    {
        return $this->getParameter('serviceId');
    }
}
