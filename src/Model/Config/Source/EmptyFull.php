<?php
/**
 * Copyright Mons Agency Ltd. Some rights reserved.
 * See copying.md for details.
 */

namespace Mons\RestrictPaymentByCarrier\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class EmptyFull implements ArrayInterface
{
    const EMPTY_VALUE = 0;
    const FULL_VALUE = 1;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::EMPTY_VALUE, 'label' => __('Do not display any payment method')],
            ['value' => self::FULL_VALUE, 'label' => __('Display every active payment method')],
        ];
    }
}
