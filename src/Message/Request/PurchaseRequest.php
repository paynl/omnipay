<?php
namespace Omnipay\PaynlV3\Message\Request;

use Omnipay\PaynlV3\Message\Response\PurchaseResponse;

class PurchaseRequest extends AbstractPaynlRequest
{
    /**
     * Regex to find streetname, housenumber and suffix out of a street string
     * @var string
     */
    private string $addressRegex = '#^([a-z0-9 [:punct:]\']*) ([0-9]{1,5})([a-z0-9 \-/]{0,})$#i';

    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('amount', 'serviceId');

        $data = [
            'serviceId' => $this->getServiceId(),
            'description' => $this->getDescription() ?: null,
            'reference' => !empty($this->getOrderNumber()) ? $this->getOrderNumber() : null,
            'exchangeUrl' => !empty($this->getNotifyUrl()) ? $this->getNotifyUrl() : null,
            'expire' => !empty($this->getExpireDate()) ? $this->getExpireDate() : null,
            'returnUrl' => $this->getReturnUrl(),
            'amount' => [
                'value' => $this->getAmountInteger(),
                'currency' => !empty($this->getCurrency()) ? $this->getCurrency() : 'EUR',
            ],
            'integration' => [
                'test' => $this->getTestMode() ?? false,
            ],
            'paymentMethod' => [
                'id' => !empty($this->getPaymentMethod()) ? $this->getPaymentMethod() : null,
                'input' => [
                    'issuerId' => !empty($this->getIssuer()) ? $this->getIssuer() : null,
                ]
            ],
            'optimize' => [],
            'customer' => [
                'ipAddress' => !empty($this->getClientIp()) ? $this->getClientIp() : null,
                'trust' => !empty($this->getCustomerTrust()) ? $this->getCustomerTrust() : null,
                'reference' => !empty($this->getCustomerReference()) ? $this->getCustomerReference() : null,
                'company' => []
            ],
            'order' => [
                'invoiceDate' => !empty($this->getInvoiceDate()) ? $this->getInvoiceDate() : null,
                'deliveryDate' => !empty($this->getDeliveryDate()) ? $this->getDeliveryDate() : null,
            ],
            'notification' => [],
            'stats' => [],
        ];

        if ($card = $this->getCard()) {

            $data['order']['deliveryAddress'] = [];
            $data['order']['invoiceAddress'] = [];

            $billingAddressParts = $this->getAddressParts($card->getBillingAddress1() . ' ' . $card->getBillingAddress2());
            $shippingAddressParts = ($card->getShippingAddress1() ? $this->getAddressParts($card->getShippingAddress1() . ' ' . $card->getShippingAddress2()) : $billingAddressParts);

            $data['customer']['email'] = !empty($card->getEmail()) ? $card->getEmail() : null;
            $data['customer']['firstname'] = !empty($card->getFirstName()) ? $card->getFirstName() : null;
            $data['customer']['lastname'] = !empty($card->getLastName()) ? $card->getLastName() : null;
            $data['customer']['birthDate'] = !empty($card->getBirthday()) ? $card->getBirthday() : null;
            $data['customer']['gender'] = !empty($card->getGender()) ? $card->getGender() : null; //Should be inserted in the CreditCard as M/F
            $data['customer']['phone'] = !empty($card->getPhone()) ? $card->getPhone() : null;
            $data['customer']['locale'] = !empty($card->getCountry()) ? substr($card->getCountry(), 0, 2) : null;
            $data['customer']['company']['name'] = !empty($card->getCompany()) ? $card->getCompany() : null;

            $data['order']['countryCode'] = !empty($card->getCountry()) ? substr($card->getCountry(), 0, 2) : null;
            $data['order']['invoiceAddress']['firstName'] = $card->getBillingFirstName();
            $data['order']['invoiceAddress']['lastName'] = $card->getBillingLastName();
            $data['order']['invoiceAddress']['street'] = $billingAddressParts[1] ?? null;
            $data['order']['invoiceAddress']['streetNumber'] = $billingAddressParts[2] ?? null;
            $data['order']['invoiceAddress']['streetNumberExtension'] = $billingAddressParts[3] ?? null;
            $data['order']['invoiceAddress']['zipCode'] = $card->getBillingPostcode();
            $data['order']['invoiceAddress']['city'] = $card->getBillingCity();
            $data['order']['invoiceAddress']['country'] = $card->getBillingCountry();
            $data['order']['invoiceAddress']['region'] = !empty($card->getBillingState()) ? $card->getBillingState() : null;

            $data['order']['deliveryAddress']['firstName'] = $card->getShippingFirstName();
            $data['order']['deliveryAddress']['lastName'] = $card->getShippingLastName();
            $data['order']['deliveryAddress']['street'] = $shippingAddressParts[1] ?? null;
            $data['order']['deliveryAddress']['streetNumber'] = $shippingAddressParts[2] ?? null;
            $data['order']['deliveryAddress']['streetNumberExtension'] = $shippingAddressParts[3] ?? null;
            $data['order']['deliveryAddress']['zipCode'] = $card->getShippingPostcode();
            $data['order']['deliveryAddress']['city'] = $card->getShippingCity();
            $data['order']['deliveryAddress']['country'] = $card->getShippingCountry();
            $data['order']['deliveryAddress']['region'] = !empty($card->getShippingState()) ? $card->getShippingState() : null;
        }

        $currency = !empty($this->getCurrency()) ? $this->getCurrency() : 'EUR';

        if ($items = $this->getItems()) {
            $data['order']['products'] = array_map(function ($item) use ($currency) {
                $data = [
                    'description' => $item->getName() ?: $item->getDescription(),
                    'price' => [
                        'value' => round($item->getPrice() * 100),
                        'currency' => $currency,
                    ],
                    'quantity' => $item->getQuantity(),
                ];
                if (method_exists($item, 'getProductId')) {
                    $data['id'] = $item->getProductId();
                } else {
                    $data['id'] = substr($item->getName(), 0, 25);
                }
                if (method_exists($item, 'getProductType')) {
                    $data['productType'] = $item->getProductType();
                }
                if (method_exists($item, 'getVatPercentage')) {
                    $data['vatPercentage'] = $item->getVatPercentage();
                }
                return $data;
            }, $items->all());
        }

        if ($statsData = $this->getStatsData()) {
            // Could be someone erroneously not set an array
            if (is_array($statsData)) {
                $allowableParams = ["promotorId", "info", "tool", "extra1", "extra2", "extra3", "domainId", "object"];
                $data['stats'] = array_filter($statsData, function($k) use ($allowableParams) {
                    return in_array($k, $allowableParams);
                }, ARRAY_FILTER_USE_KEY);
                if (!isset($data['stats']['object'])) {
                    $data['stats']['object'] = 'omnipay';
                }
            }
        }

        return $data;
    }

    /**
     * @param array $data
     * @return \Omnipay\Common\Message\ResponseInterface|PurchaseResponse
     */
    public function sendData($data)
    {
        $responseData = $this->sendRequestOrderApi($data);
        return $this->response = new PurchaseResponse($this, $responseData);
    }

    /**
     * @return array
     */
    public function getStatsData()
    {
        return $this->getParameter('statsData');
    }

    /**
     * @return mixed
     */
    public function getOrderNumber()
    {
        return $this->getParameter('orderNumber');
    }

    /**
     * @return mixed
     */
    public function getExpireDate()
    {
        return $this->getParameter('expireDate');
    }

    /**
     * @return string
     */
    public function getInvoiceDate()
    {
        return $this->getParameter('invoiceDate');
    }

    /**
     * @return string
     */
    public function getDeliveryDate()
    {
        return $this->getParameter('deliveryDate');
    }

    public function getCustomerReference()
    {
        return $this->getParameter('customerReference');
    }

    /**
     * @return int
     */
    public function getCustomerTrust()
    {
        return $this->getParameter('customerTrust');
    }

    public function getServiceId()
    {
        return $this->getParameter('serviceId');
    }

    public function setServiceId($value)
    {
        return $this->setParameter('serviceId', $value);
    }

    /**
     * Get the parts of an address
     * @param string $address
     * @return array
     */
    public function getAddressParts($address)
    {
        $addressParts = [];
        preg_match($this->addressRegex, trim($address), $addressParts);
        return array_filter($addressParts, 'trim');
    }
}
