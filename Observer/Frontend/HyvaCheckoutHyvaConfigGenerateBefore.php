<?php

declare(strict_types=1);

namespace CreditKey\B2BGatewayHyva\Observer\Frontend;

use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class HyvaCheckoutHyvaConfigGenerateBefore implements ObserverInterface
{
    private ComponentRegistrar $componentRegistrar;

    public function __construct(ComponentRegistrar $componentRegistrar)
    {
        $this->componentRegistrar = $componentRegistrar;
    }

    public function execute(Observer $observer)
    {
        $config = $observer->getData('config');
        $extensions = $config->hasData('extensions') ? $config->getData('extensions') : [];
        $moduleName = implode('_', array_slice(explode('\\', self::class), 0, 2));
        $path = $this->componentRegistrar->getPath(ComponentRegistrar::MODULE, $moduleName);

        $extensions[] = ['src' => substr((string) $path, strlen(BP) + 1)];

        $config->setData('extensions', $extensions);
    }
}
