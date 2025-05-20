<?php

declare(strict_types=1);

namespace CreditKey\B2BGatewayHyva\Magewire\Payment\Method;

use CreditKey\B2BGateway\Helper\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Magento\Framework\UrlInterface;
use Magewirephp\Magewire\Component;

class CreditKey extends Component
{
    private Config $config;

    private UrlInterface $urlBuilder;

    private JsonSerializer $jsonSerializer;

    private ScopeConfigInterface $scopeConfig;

    private string $methodCode;

    public function __construct(
        Config $config,
        UrlInterface $urlBuilder,
        JsonSerializer $jsonSerializer,
        ScopeConfigInterface $scopeConfig,
        string $methodCode = 'creditkey_gateway'
    ) {
        $this->config = $config;
        $this->urlBuilder = $urlBuilder;
        $this->jsonSerializer = $jsonSerializer;
        $this->scopeConfig = $scopeConfig;
        $this->methodCode = $methodCode;
    }

    public function getMethodCode(): string
    {
        return $this->methodCode;
    }

    public function getIframeUrl(): string
    {
        return $this->urlBuilder->getUrl('creditkey_gateway/order/create');
    }

    public function getJsConfig(): string
    {
        return $this->jsonSerializer->serialize([
            'sdkUrl' => $this->scopeConfig->getValue('payment/creditkey_gateway/sdk_url'),
            'publicKey' => $this->config->getPublicKey(),
            'endpoint' => $this->config->getEndpoint(),
            'redirectUrl' => $this->urlBuilder->getUrl('creditkey_gateway/order/create'),
        ]);
    }
}
