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
 *  AddtionalClasses - Change Classes for Inputs.
 */
class AddtionalClasses
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
     * Set Classes of Fields.
     *
     * @param array $fields
     *
     * @return array
     */
    public function setAddtionalClasses(array $fields): array
    {
        foreach ($fields as $key => $data) {
            $oldClass = null;
            if (in_array('config', $fields[$key])) {
                if ($fields[$key]['config']) {
                    $newClass = $this->config->getAddtionalClassesForField($key);
                    if ($newClass) {
                        if (in_array('additionalClasses', $fields[$key]['config'])) {
                            $oldClass = $fields[$key]['config']['additionalClasses'];
                        }
                        $fields[$key]['config']['additionalClasses'] = $oldClass.' '.$newClass;
                    }
                    if ($key === 'street') {
                        $fields = $this->setClassesOfStreetLine($fields, $key);
                    }
                }
            }
        }

        return $fields;
    }

    /**
     * Set Classes of Street Line.
     *
     * @param array  $fields
     * @param string $field
     *
     * @return array
     */
    public function setClassesOfStreetLine(array $fields, string $field): array
    {
        $oldClass = null;
        foreach ($fields[$field]['children'] as $arrayPosition => $streetLine) {
            $streetKey = 'street_'.$arrayPosition;
            $newClass = $this->config->getAddtionalClassesForField($streetKey);
            if ($newClass) {
                if (in_array('additionalClasses', $fields[$field]['children'][$arrayPosition]['config'])) {
                    $oldClass = $fields[$field]['children'][$arrayPosition]['config']['additionalClasses'];
                }
                $fields[$field]['children'][$arrayPosition]['config']['additionalClasses'] = $oldClass.' '.$newClass;
            }
        }

        return $fields;
    }
}
