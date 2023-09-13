<?php
/**
 * Copyright Mons Agency Ltd. Some rights reserved.
 * See copying.md for details.
 */

namespace Mons\RestrictPaymentByCarrier\Plugin;

use Magento\Config\Model\Config\Structure\Reader as Subject;

class ConfigStructureReaderPlugin
{
    /**
     * @param Subject $subject
     * @param array $result
     * @return array
     */
    public function afterRead(Subject $subject, array $result): array
    {
        foreach ($result['config']['system']['sections']['carriers']['children'] as &$method) {
            $method['children']['restrict_payment_methods'] = [
                'id' => 'restrict_payment_methods',
                'translate' => 'label',
                'type' => 'select',
                'sortOrder' => '990',
                'showInDefault' => '1',
                'showInWebsite' => '1',
                'showInStore' => '0',
                'canRestore' => '1',
                'label' => 'Restrict Payment Methods',
                'source_model' => 'Magento\\Config\\Model\\Config\\Source\\Yesno',
                '_elementType' => 'field',
                'path' => 'carriers/' . $method['id'],
            ];
            $method['children']['payment_methods'] = [
                'id' => 'payment_methods',
                'translate' => 'label',
                'type' => 'multiselect',
                'sortOrder' => '995',
                'showInDefault' => '1',
                'showInWebsite' => '1',
                'showInStore' => '0',
                'canRestore' => '1',
                'label' => 'Payment Methods to Display',
                'depends' => [
                    'fields' => [
                        'restrict_payment_methods' => [
                            'id' => 'carriers/' . $method['id'] . '/restrict_payment_methods',
                            'value' => '1',
                            '_elementType' => 'field',
                            'dependPath' => [
                                0 => 'carriers',
                                1 => $method['id'],
                                2 => 'restrict_payment_methods',
                            ],
                        ],
                    ],
                ],
                'source_model' => 'Magento\\Payment\\Model\\Config\\Source\\Allmethods',
                '_elementType' => 'field',
                'path' => 'carriers/' . $method['id'],
            ];
        }

        return $result;
    }
}
