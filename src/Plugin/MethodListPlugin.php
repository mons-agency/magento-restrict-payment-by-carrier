<?php
/**
 * Copyright Mons Agency Ltd. Some rights reserved.
 * See copying.md for details.
 */

namespace Mons\RestrictPaymentByCarrier\Plugin;

use Magento\Payment\Model\MethodList as Subject;
use Magento\Quote\Api\Data\CartInterface;
use Mons\RestrictPaymentByCarrier\Helper\Data as Helper;
use Mons\RestrictPaymentByCarrier\Model\Config\Source\EmptyFull;

class MethodListPlugin
{
    /**
     * @param Helper $helper
     */
    public function __construct(
        private Helper $helper
    )
    {}

    /**
     * @param Subject $subject
     * @param array $paymentMethods
     * @param CartInterface $quote
     * @return array
     */
    public function afterGetAvailableMethods(
        Subject $subject,
        array $paymentMethods,
        CartInterface $quote = null
    )
    {
        // no active quote or undefined shipping address
        if (!$quote || !$quote->getShippingAddress()) {
            return $this->helper->getDefaultBehaviour() === EmptyFull::EMPTY_VALUE ? [] : $paymentMethods;
        }

        // explode shipping carrier and method (Magento\Checkout\Mode\TotalsInformationManagement::calculate())
        list($shippingCarrier, $shippingMethod) = explode(
            '_',
            $quote->getShippingAddress()->getShippingMethod() ?: ''
        );

        // disabled functionality = returns the active payment methods
        if (!$this->helper->isEnabled($shippingCarrier)) {
            return $paymentMethods;
        }

        $allowedPaymentMethods = $this->helper->getPaymentMethods($shippingCarrier);

        // remove unwanted payment methods
        return array_filter(
            $paymentMethods,
            function($method) use ($allowedPaymentMethods) {
                return in_array($method->getCode(), $allowedPaymentMethods);
            }
        );
    }
}
