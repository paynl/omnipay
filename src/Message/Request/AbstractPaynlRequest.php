<?php

namespace Omnipay\PaynlV3\Message\Request;

use GuzzleHttp\Exception\ConnectException;
use Omnipay\Common\Message\AbstractRequest;

/**
 * Class OrderAbstractPaynlRequest
 * @package Omnipay\Paynl\Message\Request
 */
abstract class AbstractPaynlRequest extends AbstractRequest
{
    /**
     * @var string
     */
    private string $restUri = 'https://rest.pay.nl/v2/';
    private string $baseUrlPrefix = 'https://connect.';
    private string $ordersEndpoint = 'v1/orders';


    /**
     * @param array $data
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function sendRequestOrderApi(array $data)
    {
        $coreDomains = $this->getCoreDomains();
        $lastResult = null;

        foreach ($coreDomains as $coreDomain) {
            try {
                $url = $this->baseUrlPrefix . $coreDomain . '/' . $this->ordersEndpoint;
                $response = $this->sendRequest($url, $data, 'POST', false);

                if ($response->getStatusCode() === 500 || $response->getStatusCode() === 503 || $response->getStatusCode() == null) {
                    $lastResult = $response;
                    continue;
                }

                // If we get here, the request was successful, so return immediately
                return json_decode($response->getBody(), true);
            } catch (ConnectException) {
                // Connection failed (domain is down), store response and continue to next domain
                continue;
            }
        }

        // If we get here, all domains failed
        return json_decode($lastResult->getBody(), true);
    }

    public function sendRequestRestApi(string $endpoint, array $data = null, string $method = 'GET')
    {
        $url = $this->restUri . $endpoint;

        return $this->sendRequest($url, $data, $method);
    }


    public function sendRequest(string $url, array $data = null, string $method = 'GET', bool $returnOnlyBody = true)
    {
        $headers = $this->getAuthHeader();
        $headers += ['Content-Type' => 'application/json', 'accept' => 'application/json'];

        $body = null;

        if (!is_null($data)) {
            $body = json_encode($data);
        }

        $response = $this->httpClient->request($method, $url, $headers, $body);
        if ($returnOnlyBody)
        {
            return json_decode($response->getBody(), true);
        }

        return $response;
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

    /**
     * @return array
     */
    public function getCoreDomains(): array
    {
        $coreDomains = $this->getParameter('coreDomains');
        if (!isset($coreDomains) || count($coreDomains) == 0) {
            return ['pay.nl'];
        }

        return $coreDomains;
    }
}
