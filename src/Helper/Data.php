<?php
/**
 * Copyright Mons Agency Ltd. Some rights reserved.
 * See copying.md for details.
 */

namespace Mons\RestrictPaymentByCarrier\Helper;

use Magento\Config\Model\Config\Source\Enabledisable;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use Mons\RestrictPaymentByCarrier\Model\Config\Source\EmptyFull;

class Data extends AbstractHelper
{
    const XML_PATH_DEFAULT_BEHAVIOUR = 'shipping/restrict_payment_methods/default_behaviour';
    const XML_PATH_ENABLED = 'carriers/%s/restrict_payment_methods';
    const XML_PATH_PAYMENT_METHODS = 'carriers/%s/payment_methods';

    /**
     * Getter
     *
     * @param string $shippingCarrier
     * @return bool
     */
    public function isEnabled(string $shippingCarrier): bool
    {
        $value = $this->scopeConfig->getValue(
            sprintf(self::XML_PATH_ENABLED, $shippingCarrier),
            ScopeInterface::SCOPE_WEBSITES,
            null
        );

        return $value === Enabledisable::ENABLE_VALUE;
    }

    /**
     * Getter
     *
     * @return string
     */
    public function getDefaultBehaviour(): string
    {
        $value = $this->scopeConfig->getValue(
            self::XML_PATH_DEFAULT_BEHAVIOUR,
            ScopeInterface::SCOPE_WEBSITES,
            null
        );

        return $value ?: EmptyFull::EMPTY_VALUE;
    }

    /**
     * Getter
     *
     * @param string $shippingCarrier
     * @return array
     */
    public function getPaymentMethods(string $shippingCarrier): array
    {
        $value = $this->scopeConfig->getValue(
            sprintf(self::XML_PATH_PAYMENT_METHODS, $shippingCarrier),
            ScopeInterface::SCOPE_WEBSITES,
            null
        );

        return explode(',', $value ?: '');
    }
}
