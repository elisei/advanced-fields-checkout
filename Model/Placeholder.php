<?php
/**
 * Copyright © O2TI. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See COPYING.txt for license details.
 */

namespace O2TI\AdvancedFieldsCheckout\Model;

use O2TI\AdvancedFieldsCheckout\Helper\Config;

/**
 *  Placeholder - Implements Placeholder for Inputs.
 */
class Placeholder
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * Set Components Placeholder in Fields.
     *
     * @param array $fields
     *
     * @return array
     */
    public function setPlaceholder(array $fields): array
    {
        foreach ($fields as $key => $data) {
            if (in_array('label', $fields[$key])) {
                if ($fields[$key]['label']) {
                    $placeholder = $this->config->getPlaceholderForField($key);
                    $label = $fields[$key]['label'];
                    $fields[$key]['config']['placeholder'] = __($label);
                    if ($placeholder) {
                        $fields[$key]['config']['placeholder'] = __($placeholder);
                    }
                    if ($key === 'street') {
                        $fields = $this->setPlaceholderOfStreetLine($fields, $key);
                    }
                }
            }
        }

        return $fields;
    }

    /**
     * Set Components Placeholder of Street Lines.
     *
     * @param array  $fields
     * @param string $field
     *
     * @return array
     */
    public function setPlaceholderOfStreetLine(array $fields, string $field): array
    {
        foreach ($fields[$field]['children'] as $arrayPosition => $streetLine) {
            $streetKey = 'street_'.$arrayPosition;
            $placeholder = $this->config->getPlaceholderForField($streetKey);
            $label = $fields[$field]['children'][$arrayPosition]['label'];
            $fields[$field]['children'][$arrayPosition]['config']['placeholder'] = __($label);
            if ($placeholder) {
                $fields[$field]['children'][$arrayPosition]['config']['placeholder'] = __($placeholder);
            }
        }

        return $fields;
    }
}
