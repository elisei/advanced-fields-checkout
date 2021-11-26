<?php
/**
 * Copyright © O2TI. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace O2TI\AdvancedFieldsCheckout\Block\Adminhtml\Form\Field\Column;

use Magento\Customer\Model\Address;
use Magento\Customer\Model\Customer;
use Magento\Eav\Api\Data\AttributeInterface;
use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;
use Magento\Ui\Component\Form\AttributeMapper;

/**
 * Class FieldColumn - Create Field to Column.
 */
class FieldColumn extends Select
{
    /**
     * @var Address
     */
    private $address;

    /**
     * @var Customer
     */
    private $customer;

    /**
     * @var AttributeMapper
     */
    protected $attributeMapper;

    /**
     * Constructor.
     *
     * @param Context         $context
     * @param Address         $address
     * @param Customer        $customer
     * @param AttributeMapper $attributeMapper
     * @param array           $data
     */
    public function __construct(
        Context $context,
        Address $address,
        Customer $customer,
        AttributeMapper $attributeMapper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->address = $address;
        $this->customer = $customer;
    }

    /**
     * Set "name" for <select> element.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    /**
     * Set "id" for <select> element.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setInputId($value)
    {
        return $this->setId($value);
    }

    /**
     * Render block HTML.
     *
     * @return string
     */
    public function _toHtml(): string
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->getSourceOptions());
        }

        return parent::_toHtml();
    }

    /**
     * Render Options.
     *
     * @return array
     */
    private function getSourceOptions(): array
    {
        $customerAttributes = $this->getCustomerAttributes();

        foreach ($customerAttributes as $atribute => $val) {
            $attributesArrays[] = [
                'label' => __($val['label']).' - '.$atribute,
                'value' => $atribute,
            ];
        }

        return $attributesArrays;
    }

    /**
     * Get customer attributes.
     *
     * @return array
     */
    private function getCustomerAttributes(): array
    {
        $elements = [];

        /** @var AttributeInterface[] $attributes */
        $attributesCustomer = $this->customer->getAttributes();

        foreach ($attributesCustomer as $attribute) {
            $code = $attribute->getAttributeCode();
            $inForms = $attribute->getUsedInForms();
            $forms = [];
            foreach ($inForms as $value) {
                $forms[] = $value;
            }
            if (!in_array('customer_account_create', $forms)) {
                continue;
            }
            if ((int) $attribute->getData('is_visible') === 0) {
                continue;
            }
            $elements[$code]['label'] = $attribute->getData('frontend_label');
        }

        $attributesAddress = $this->address->getAttributes();

        foreach ($attributesAddress as $attribute) {
            $code = $attribute->getAttributeCode();
            $inForms = $attribute->getUsedInForms();
            $forms = [];
            foreach ($inForms as $value) {
                $forms[] = $value;
            }
            if (!in_array('customer_register_address', $forms)) {
                continue;
            }
            if ((int) $attribute->getData('is_visible') === 0) {
                continue;
            }
            $elements[$code]['label'] = $attribute->getData('frontend_label');
        }
        $elements['password']['label'] = 'Password';
        $elements['street_0']['label'] = 'Street Line 1';
        $elements['street_1']['label'] = 'Street Line 2';
        $elements['street_2']['label'] = 'Street Line 3';
        $elements['street_3']['label'] = 'Street Line 4';

        return $elements;
    }
}
