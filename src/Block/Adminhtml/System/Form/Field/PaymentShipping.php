<?php
/**
 * Copyright Mons Agency Ltd. Some rights reserved.
 * See copying.md for details.
 */

namespace Mons\RestrictPaymentByCarrier\Block\Adminhtml\System\Form\Field;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\BlockInterface;

class PaymentShipping extends AbstractFieldArray
{
    /**
     * @var array
     */
    protected $columns = [];
    /**
     * @var BlockInterface
     */
    protected $paymentTypeRenderer;
    /**
     * @var BlockInterface
     */
    protected $shippingTypeRenderer;
    /**
     * @var mixed
     */
    protected $searchFieldRenderer;

    /**
     * @param ActiveMethods $activeMethods
     * @param Context $context
     * @param array $data
     */
    // public function __construct(
    //     protected ActiveMethods $activeMethods,
    //     Context $context,
    //     array $data = []
    // ) {
    //     parent::__construct($context, $data);
    // }

    /**
     * Prepare to render
     *
     * @throws LocalizedException
     */
    protected function _prepareToRender()
    {
        $this->paymentTypeRenderer = null;
        $this->searchFieldRenderer = null;

        $this->addColumn(
            'payment_method',
            [
                'label' => __('Payment Method'),
                'renderer' => $this->getPaymentRenderer(),
            ]
        );
        $this->addColumn(
            'shipping_method',
            [
                'label' => __('Shipping Method'),
                'renderer' => $this->getShippingRenderer(),
            ]
        );

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Restriction');
    }

    /**
     * Get payment renderer
     *
     * @return BlockInterface
     * @throws LocalizedException
     */
    protected function getPaymentRenderer()
    {
        if (!$this->paymentTypeRenderer) {
            $this->paymentTypeRenderer = $this->getLayout()->createBlock(
                PaymentMethods::class,
                '',
                [
                    'data' => [
                        'is_render_to_js_template' => true,
                    ],
                ]
            );

            $this->paymentTypeRenderer->setClass('payment_select');
        }

        return $this->paymentTypeRenderer;
    }

    /**
     * Get shipping renderer
     *
     * @return BlockInterface
     * @throws LocalizedException
     */
    protected function getShippingRenderer()
    {
        if (!$this->shippingTypeRenderer) {
            $this->shippingTypeRenderer = $this->getLayout()->createBlock(
                ShippingMethods::class,
                '',
                [
                    'data' => [
                        'is_render_to_js_template' => true,
                    ],
                ]
            );

            $this->shippingTypeRenderer->setClass('shipping_select');
        }

        return $this->shippingTypeRenderer;
    }

    /**
     * Prepare existing row data object
     *
     * @param DataObject $row
     * @throws LocalizedException
     */
    protected function _prepareArrayRow(DataObject $row): void
    {
        $optionExtraAttr = [
            'option_' . $this->getPaymentRenderer()->calcOptionHash($row->getData('payment_method')) => 'selected="selected"',
        ];

        $row->setData(
            'option_extra_attrs',
            $optionExtraAttr
        );
    }
}
