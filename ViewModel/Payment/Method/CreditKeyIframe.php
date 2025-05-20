<?php

declare(strict_types=1);

namespace CreditKey\B2BGatewayHyva\ViewModel\Payment\Method;

use Magento\Checkout\Model\Session as SessionCheckout;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\Order;

class CreditKeyIframe implements ArgumentInterface
{
    private ?string $orderPaymentCode = null;

    public function __construct(private readonly SessionCheckout $sessionCheckout, private readonly UrlInterface $urlBuilder)
    {
    }

    public function getMethodCode(): string
    {
        if ($this->orderPaymentCode === null) {
            $order = $this->sessionCheckout->getLastRealOrder();

            if (!$this->isIframeAllowed($order)) {
                throw new LocalizedException(__('This payment is not allowed to pay with credit card'));
            }

            $this->orderPaymentCode = $order->getPayment()
                ->getMethod();
        }

        return $this->orderPaymentCode;
    }

    public function getIframeUrl(): string
    {
        return $this->urlBuilder->getUrl('creditkey_gateway_hyva/order/create');
    }

    private function isIframeAllowed(OrderInterface $order): bool
    {
        $orderPayment = $order->getPayment();

        return $order->getId() &&
            $order->getState() === Order::STATE_PENDING_PAYMENT &&
            $orderPayment &&
            $orderPayment->getMethod() === 'creditkey_gateway';
    }
}
