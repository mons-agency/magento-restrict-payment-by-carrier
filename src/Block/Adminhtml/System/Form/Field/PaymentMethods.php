<?php
/**
 * Copyright Mons Agency Ltd. Some rights reserved.
 * See copying.md for details.
 */

namespace Mons\RestrictPaymentByCarrier\Block\Adminhtml\System\Form\Field;

use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;
use Magento\Store\Model\ScopeInterface;

class PaymentMethods extends Select
{
    const XML_PATH_PAYMENT_METHODS = 'payment';

    /**
     * @var array
     */
    protected $paymentMethods;

    /**
     * Getter
     *
     * @return array
     */
    protected function getPaymentMethods(): array
    {
        if ($this->paymentMethods === null) {
            $methods = $this->_scopeConfig->getValue(
                self::XML_PATH_PAYMENT_METHODS,
                ScopeInterface::SCOPE_STORE,
                null
            );

            foreach ($methods as $code => $data) {
                if (isset($data['title'])) {
                    $this->paymentMethods[$code] = $data['title'];
                }
            }
        }

        return $this->paymentMethods;
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
            foreach ($this->getPaymentMethods() as $code => $title) {
                $title = $code . ' (' . $title . ')';

                $this->addOption($code, addslashes($title));
            }
        }

        $this->setExtraParams('multiple="multiple"');

        return parent::_toHtml();
    }
}
