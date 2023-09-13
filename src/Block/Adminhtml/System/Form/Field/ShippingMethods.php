<?php
/**
 * Copyright Mons Agency Ltd. Some rights reserved.
 * See copying.md for details.
 */

namespace Mons\RestrictPaymentByCarrier\Block\Adminhtml\System\Form\Field;

use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;
use Magento\Store\Model\ScopeInterface;

class ShippingMethods extends Select
{
    const XML_PATH_SHIPPING_METHODS = 'carriers';

    /**
     * @var array
     */
    protected $shippingMethods;

    /**
     * Getter
     *
     * @return array
     */
    protected function getshippingMethods(): array
    {
        if ($this->shippingMethods === null) {
            $methods = $this->_scopeConfig->getValue(
                self::XML_PATH_SHIPPING_METHODS,
                ScopeInterface::SCOPE_STORE,
                null
            );

            foreach ($methods as $code => $data) {
                if (isset($data['title'])) {
                    $this->shippingMethods[$code] = $data['title'];
                }
            }
        }

        return $this->shippingMethods;
    }

    /**
     * Setter
     *
     * @param string $value
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    /**
     * {@inheritDoc}
     */
    public function _toHtml()
    {
        if (!$this->getOptions()) {
            foreach ($this->getshippingMethods() as $code => $title) {
                $title = $title . ' (' . $code . ')';

                $this->addOption($code, addslashes($title));
            }
        }

        $this->setExtraParams('multiple="multiple"');

        return parent::_toHtml();
    }
}
