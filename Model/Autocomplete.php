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
 *  Autocomplete - Implements Autocomplete for Inputs.
 */
class Autocomplete
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
     * Set Components Autocomplete in Fields.
     *
     * @param array $fields
     *
     * @return array
     */
    public function setAutocomplete(array $fields): array
    {
        foreach ($fields as $key => $data) {
            if (in_array('config', $fields[$key])) {
                if ($fields[$key]['config']) {
                    $autocomplete = $this->config->getAutocompleteForField($key);
                    if ($autocomplete) {
                        $fields[$key]['config']['autocomplete'] = $autocomplete;
                        if ($fields[$key]['config']['elementTmpl'] === 'ui/form/element/input') {
                            $fields[$key]['config']['elementTmpl'] = 'O2TI_AdvancedFieldsCheckout/form/element/input';
                        }
                        if ($fields[$key]['config']['elementTmpl'] === 'ui/form/element/select') {
                            $fields[$key]['config']['elementTmpl'] = 'O2TI_AdvancedFieldsCheckout/form/element/select';
                        }
                        if ($fields[$key]['config']['elementTmpl'] === 'ui/form/element/password') {
                            // phpcs:ignore
                            $fields[$key]['config']['elementTmpl'] = 'O2TI_AdvancedFieldsCheckout/form/element/password';
                        }
                        // phpcs:ignore
                        if ($fields[$key]['config']['elementTmpl'] === 'O2TI_CheckoutIdentificationStep/form/element/password') {
                            // phpcs:ignore
                            $fields[$key]['config']['elementTmpl'] = 'O2TI_AdvancedFieldsCheckout/form/element/O2TI/password';
                        }
                    }
                    if ($key === 'street') {
                        $fields = $this->setAutocompleteOfStreetLine($fields, $key);
                    }
                }
            }
        }

        return $fields;
    }

    /**
     * Set Components Autocomplete in Street Lines.
     *
     * @param array  $fields
     * @param string $field
     *
     * @return array
     */
    public function setAutocompleteOfStreetLine(array $fields, string $field): array
    {
        foreach ($fields[$field]['children'] as $arrayPosition => $streetLine) {
            $streetKey = 'street_'.$arrayPosition;
            $autocomplete = $this->config->getAutocompleteForField($streetKey);
            if ($autocomplete) {
                if ($fields[$field]['children'][$arrayPosition]['config']['elementTmpl'] === 'ui/form/element/input') {
                    // phpcs:ignore
                    $fields[$field]['children'][$arrayPosition]['config']['elementTmpl'] = 'O2TI_AdvancedFieldsCheckout/form/element/input';
                }
                // phpcs:ignore
                if ($fields[$field]['children'][$arrayPosition]['config']['elementTmpl'] === 'O2TI_AdvancedStreetAddress/form/element/number') {
                    // phpcs:ignore
                    $fields[$field]['children'][$arrayPosition]['config']['elementTmpl'] = 'O2TI_AdvancedFieldsCheckout/form/element/number';
                }

                $fields[$field]['children'][$arrayPosition]['config']['autocomplete'] = $autocomplete;
            }
        }

        return $fields;
    }
}
